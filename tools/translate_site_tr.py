import re
from typing import Dict, List, Tuple

import mysql.connector
from deep_translator import GoogleTranslator


DB_CONFIG = {
    "user": "root",
    "password": "Hakan1987$Tr",
    "host": "localhost",
    "database": "marseylove",
}

TRANSLATOR = GoogleTranslator(source="auto", target="tr")


def split_chunks(text: str, limit: int = 3500) -> List[str]:
    if len(text) <= limit:
        return [text]

    pieces = re.split(r"(\n\n+)", text)
    chunks: List[str] = []
    current = ""

    for piece in pieces:
        if len(current) + len(piece) <= limit:
            current += piece
            continue
        if current:
            chunks.append(current)
            current = ""
        if len(piece) <= limit:
            current = piece
        else:
            for i in range(0, len(piece), limit):
                chunks.append(piece[i : i + limit])
    if current:
        chunks.append(current)
    return chunks


def protect_fragments(text: str) -> Tuple[str, Dict[str, str]]:
    patterns = [
        r"https?://[^\s\"')]+",
        r"<[^>]+>",
        r"\[[^\[\]]+\]",
    ]
    combined = re.compile("|".join(patterns), flags=re.IGNORECASE | re.DOTALL)

    replacements: Dict[str, str] = {}
    index = 0

    def replacer(match: re.Match) -> str:
        nonlocal index
        key = f"__PH_{index}__"
        replacements[key] = match.group(0)
        index += 1
        return key

    protected = combined.sub(replacer, text)
    return protected, replacements


def restore_fragments(text: str, replacements: Dict[str, str]) -> str:
    for key, value in replacements.items():
        text = text.replace(key, value)
    return text


def translate_text(value: str) -> str:
    text = (value or "").strip()
    if not text:
        return value

    protected, replacements = protect_fragments(value)
    chunks = split_chunks(protected)
    translated_parts = []
    for chunk in chunks:
        translated_parts.append(TRANSLATOR.translate(chunk))
    translated = "".join(translated_parts)
    return restore_fragments(translated, replacements)


def ensure_turkish_locale(cursor) -> None:
    cursor.execute(
        """
        INSERT INTO wp_options (option_name, option_value, autoload)
        VALUES ('WPLANG', 'tr_TR', 'yes')
        ON DUPLICATE KEY UPDATE option_value = 'tr_TR'
        """
    )


def translate_options(cursor) -> None:
    cursor.execute(
        "SELECT option_name, option_value FROM wp_options WHERE option_name IN ('blogname', 'blogdescription')"
    )
    for option_name, option_value in cursor.fetchall():
        translated = translate_text(option_value or "")
        cursor.execute(
            "UPDATE wp_options SET option_value = %s WHERE option_name = %s",
            (translated, option_name),
        )


def translate_posts(cursor) -> None:
    cursor.execute(
        """
        SELECT ID, post_type, post_title, post_excerpt, post_content
        FROM wp_posts
        WHERE post_status NOT IN ('trash', 'auto-draft', 'inherit')
          AND post_type IN ('post', 'page', 'product', 'portfolio', 'nav_menu_item')
        """
    )
    rows = cursor.fetchall()

    for row in rows:
        post_id, post_type, post_title, post_excerpt, post_content = row

        new_title = translate_text(post_title or "")
        new_excerpt = translate_text(post_excerpt or "")
        new_content = translate_text(post_content or "")

        cursor.execute(
            """
            UPDATE wp_posts
            SET post_title = %s,
                post_excerpt = %s,
                post_content = %s
            WHERE ID = %s
            """,
            (new_title, new_excerpt, new_content, post_id),
        )

        print(f"Translated {post_type} #{post_id}")


def translate_terms(cursor) -> None:
    cursor.execute("SELECT term_id, name, slug FROM wp_terms")
    terms = cursor.fetchall()

    for term_id, name, slug in terms:
        translated_name = translate_text(name or "")
        translated_slug = re.sub(r"[^a-z0-9-]", "-", translated_name.lower())
        translated_slug = re.sub(r"-+", "-", translated_slug).strip("-") or slug

        cursor.execute(
            "UPDATE wp_terms SET name = %s, slug = %s WHERE term_id = %s",
            (translated_name, translated_slug, term_id),
        )

    cursor.execute("SELECT term_id, description FROM wp_term_taxonomy")
    tax_rows = cursor.fetchall()
    for term_id, description in tax_rows:
        translated_desc = translate_text(description or "")
        cursor.execute(
            "UPDATE wp_term_taxonomy SET description = %s WHERE term_id = %s",
            (translated_desc, term_id),
        )


def run() -> None:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()

    try:
        ensure_turkish_locale(cursor)
        translate_options(cursor)
        translate_posts(cursor)
        translate_terms(cursor)
        conn.commit()
        print("Site content translated to Turkish and locale set to tr_TR.")
    except Exception:
        conn.rollback()
        raise
    finally:
        cursor.close()
        conn.close()


if __name__ == "__main__":
    run()