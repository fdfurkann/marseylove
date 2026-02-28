<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function marsey_force_tr_labels( $translated_text, $text, $domain ) {
    $map = array(
        'Search' => 'Ara',
        'Cart' => 'Sepet',
        'Shop Now' => 'Şimdi Satın Al',
        'Menu' => 'Menü',
        'Wishlist' => 'Favoriler',
        'My Account' => 'Hesabım',
        'Add to cart' => 'Sepete Ekle',
    );

    if ( isset( $map[ $text ] ) ) {
        return $map[ $text ];
    }

    if ( isset( $map[ $translated_text ] ) ) {
        return $map[ $translated_text ];
    }

    return $translated_text;
}
add_filter( 'gettext', 'marsey_force_tr_labels', 999, 3 );
add_filter( 'ngettext', 'marsey_force_tr_labels', 999, 3 );
