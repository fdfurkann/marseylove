<?php
/**
 * Product taxonomy archive header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$is_sale_page = $wp_query->is_sale_page;
$is_latest_page = $wp_query->is_latest_page;
$is_main_shop = is_shop();

// OnSale page plugin
if ( $is_sale_page || $is_latest_page ) {
	$is_main_shop = false;	
}

// Get category ID
$cate = get_queried_object();
$cateID = ( ! is_shop() ) ? $cate->term_id : false;

// Shop Header Style
$shop_hero_title = goya_meta_config('shop','hero_title','none');
$shop_header_bg = get_theme_mod( 'shop_header_bg', array() );

$header_class[] = 'hero-header';
$header_bg_class = array();

if ( $shop_hero_title != 'none') {
	
	if ( $is_main_shop && ! is_search() ) {
		if (! empty (get_theme_mod('shop_header_bg_image', '') ) ) {
			$header_bg_class[] = 'parallax_image';
			$header_bg_class[] = 'vh-height';
		}
		$header_bg_class[] = 'hero-title';
	} else if ( is_tax() ) { 
		$term = get_queried_object();
		$term_id = $term->term_id;
		$header_id = get_term_meta( $term_id, 'header_id', true );
		$image = wp_get_attachment_url($header_id, 'full');

		if (! empty($image)) {
			$header_bg_class[] = 'parallax_image';
			$header_bg_class[] = 'vh-height';
		}

		if ($shop_hero_title != 'main-hero') {
			$header_bg_class[] = 'hero-title';
		} else {
			$header_class[] = 'page-padding';
			$header_bg_class[] = 'regular-title';
		}
	} else if ( $is_sale_page || $is_latest_page) {
		$image_url = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
		if ($image_url) {
			$header_bg_class[] = 'parallax_image';
			$header_bg_class[] = 'vh-height';
		}
		if ($shop_hero_title != 'main-hero') {
			$header_bg_class[] = 'hero-title';
		} else {
			$header_class[] = 'page-padding';
			$header_bg_class[] = 'regular-title';
		}
	} else if ($shop_hero_title != 'main-hero') {
		$header_bg_class[] = 'hero-title';
	} else {
		$header_class[] = 'page-padding';
		$header_bg_class[] = 'regular-title';
	}
} else {
	$header_class[] = 'page-padding';
	$header_bg_class[] = 'regular-title';
}

?>
<div class="<?php echo esc_attr(implode(' ', $header_class)); ?>">
	<div class="<?php echo esc_attr(implode(' ', $header_bg_class)); ?>">
		<div class="container hero-header-container">
			<header class="row woocommerce-products-header">
				<div class="col-lg-8">
					<?php if ( $is_main_shop && !is_search() && get_theme_mod( 'shop_homepage_title_hide', false ) == true) {
						add_filter( 'woocommerce_show_page_title', function() { return false; });
					}

					/**
					 * Hook: woocommerce_show_page_title.
					 *
					 * Allow developers to remove the product taxonomy archive page title.
					 *
					 * @since 2.0.6.
					 */

					if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
						<h1 class="et-shop-title woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
					<?php endif; ?>
					<?php
					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					?>

					<?php if ( ( $is_main_shop || is_product_category() ) && !is_search() && get_theme_mod('shop_categories_list', true) == true ) {
						goya_subcategories_by_id( apply_filters( 'goya_shop_header_subcategories', $cateID ) );
					} ?>
				</div>
			</header>
		</div>
	</div>
</div>
