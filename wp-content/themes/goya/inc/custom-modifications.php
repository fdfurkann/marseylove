<?php
/**
 * Custom Modifications for Marsey1 Site
 * - Enqueue custom homepage styles
 * - Remove specific footer menu items
 */

// Enqueue custom CSS for homepage modifications
function marsey_custom_homepage_styles() {
	wp_enqueue_style(
		'marsey-custom-homepage',
		GOYA_ASSET_CSS . '/custom-homepage-styles.css',
		array('goya-core'),
		GOYA_THEME_VERSION,
		'all'
	);
	
	wp_enqueue_style(
		'marsey-custom-slider-fix',
		GOYA_ASSET_CSS . '/custom-slider-fix.css',
		array('goya-core'),
		GOYA_THEME_VERSION,
		'all'
	);
	
	// Enqueue custom JavaScript for clickable titles
	wp_enqueue_script(
		'marsey-custom-homepage-js',
		GOYA_ASSET_JS . '/custom-homepage.js',
		array('jquery'),
		GOYA_THEME_VERSION,
		true
	);
}
add_action('wp_enqueue_scripts', 'marsey_custom_homepage_styles', 15);


// Add logo above footer
function marsey_add_footer_logo() {
	$logo_id = 4240; // MarseyLove logo ID
	$logo_url = wp_get_attachment_image_url($logo_id, 'full');
	
	if ($logo_url) {
		echo '<div class="footer-logo-section" style="text-align: center; padding: 60px 0 40px; background: #fff;">';
		echo '<div class="container">';
		echo '<img src="' . esc_url($logo_url) . '" alt="MarseyLove" style="max-width: 200px; height: auto;" />';
		echo '</div>';
		echo '</div>';
	}
}
add_action('goya_footer', 'marsey_add_footer_logo', 5); // Priority 5 to run before footer widgets


// Remove specific items from footer Help menu
function marsey_remove_footer_menu_items($items, $args) {
	// Only target the Help menu (menu ID 34)
	if (isset($args->menu) && $args->menu == 34) {
		foreach ($items as $key => $item) {
			// Remove privacy policy (ID 4541) and any other privacy/return policy items
			// Target by ID, URL slug, or title
			if (
				$item->ID == 4541 || // Privacy Policy menu item
				$item->object_id == 4493 || // Gizlilik Politikası page
				$item->object_id == 4491 || // İade Politikası page
				strpos($item->url, 'privacy-policy') !== false ||
				strpos($item->url, 'gizlilik-politikasi') !== false ||
				strpos($item->url, 'iade-politikasi') !== false ||
				stripos($item->title, 'gizlilik') !== false ||
				stripos($item->title, 'iade') !== false ||
				stripos($item->title, 'Privacy Policy') !== false
			) {
				unset($items[$key]);
			}
		}
	}
	return $items;
}
add_filter('wp_nav_menu_objects', 'marsey_remove_footer_menu_items', 10, 2);

// Replace VIEW buttons with product prices directly in HTML output
function marsey_replace_view_with_price_content($content) {
	// Only process if WooCommerce is active
	if (!function_exists('wc_get_product')) {
		return $content;
	}
	
	// Match all VIEW button links with product URLs
	$pattern = '/(<a[^>]*href="([^"]*\/product\/([^\/\?"]+)[^"]*)"[^>]*class="[^"]*et-banner-link[^"]*"[^>]*>)\s*VIEW\s*(<\/a>)/i';
	
	$content = preg_replace_callback($pattern, function($matches) {
		$full_match = $matches[0];
		$link_open = $matches[1];
		$product_url = $matches[2];
		$product_slug = $matches[3];
		$link_close = $matches[4];
		
		// Try to get product by slug
		$args = array(
			'name' => $product_slug,
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => 1
		);
		$products = get_posts($args);
		
		// If we found the product, get its price
		if (!empty($products)) {
			$product = wc_get_product($products[0]->ID);
			if ($product) {
				$price_html = $product->get_price_html();
				// Strip HTML tags to get clean text
				$price_text = strip_tags($price_html);
				
				// Return the link with price instead of VIEW
				return $link_open . $price_text . $link_close;
			}
		}
		
		// If we couldn't find the product, return original
		return $full_match;
	}, $content);
	
	return $content;
}
add_filter('the_content', 'marsey_replace_view_with_price_content', 20);

// Add rental functionality to product pages
// Add rental price custom field to product
function marsey_add_rental_price_field() {
	woocommerce_wp_text_input(
		array(
			'id' => '_rental_price',
			'label' => __('Rental Price (₺)', 'goya'),
			'placeholder' => '',
			'desc_tip' => 'true',
			'description' => __('Enter product rental price', 'goya'),
			'type' => 'number',
			'custom_attributes' => array(
				'step' => 'any',
				'min' => '0'
			)
		)
	);
}
add_action('woocommerce_product_options_pricing', 'marsey_add_rental_price_field');

// Save rental price field
function marsey_save_rental_price_field($post_id) {
	$rental_price = isset($_POST['_rental_price']) ? sanitize_text_field($_POST['_rental_price']) : '';
	update_post_meta($post_id, '_rental_price', $rental_price);
}
add_action('woocommerce_process_product_meta', 'marsey_save_rental_price_field');

// Add rental option to single product page
function marsey_add_rental_option_to_product() {
	global $product;
	
	$rental_price = get_post_meta($product->get_id(), '_rental_price', true);
	
	if (!empty($rental_price)) {
		?>
		<div class="marsey-rental-option" style="margin-bottom: 20px;">
			<div class="rental-toggle-wrapper" style="display: flex; gap: 15px; margin-bottom: 15px;">
				<label style="display: flex; align-items: center; cursor: pointer; padding: 12px 24px; border: 2px solid #000; background: #fff; transition: all 0.3s;">
					<input type="radio" name="purchase_type" value="buy" checked style="margin-right: 8px;">
					<span style="font-weight: 500;">Buy</span>
				</label>
				<label style="display: flex; align-items: center; cursor: pointer; padding: 12px 24px; border: 2px solid #000; background: #fff; transition: all 0.3s;">
					<input type="radio" name="purchase_type" value="rent" style="margin-right: 8px;">
					<span style="font-weight: 500;">Rent</span>
				</label>
			</div>
			
			<div class="price-display" style="margin-bottom: 15px;">
				<span class="current-price-label" style="font-size: 14px; color: #666;">Purchase Price:</span>
				<span class="current-price-value" style="font-size: 20px; font-weight: 600; color: #000; margin-left: 10px;"><?php echo wc_price($product->get_price()); ?></span>
			</div>
			
			<input type="hidden" name="is_rental" id="is_rental" value="0">
			<input type="hidden" name="rental_price" id="rental_price_input" value="<?php echo esc_attr($rental_price); ?>">
			<input type="hidden" name="original_price" id="original_price_input" value="<?php echo esc_attr($product->get_price()); ?>">
		</div>
		
		<script>
		jQuery(document).ready(function($) {
			var rentalPrice = <?php echo floatval($rental_price); ?>;
			var originalPrice = <?php echo floatval($product->get_price()); ?>;
			
			$('input[name="purchase_type"]').on('change', function() {
				var $labels = $('.rental-toggle-wrapper label');
				$labels.css({'background': '#fff', 'color': '#000'});
				$(this).parent().css({'background': '#000', 'color': '#fff'});
				$(this).parent().find('span').css('color', '#fff');
				
				if ($(this).val() === 'rent') {
					$('#is_rental').val('1');
					$('.current-price-label').text('Rental Price:');
					$('.current-price-value').html('<?php echo wc_price(' + rentalPrice + '); ?>'.replace(/[0-9,.]+/, rentalPrice.toFixed(2).replace('.', ',')));
					$('.single_add_to_cart_button').text('Rent Now');
				} else {
					$('#is_rental').val('0');
					$('.current-price-label').text('Purchase Price:');
					$('.current-price-value').html('<?php echo wc_price(' + originalPrice + '); ?>'.replace(/[0-9,.]+/, originalPrice.toFixed(2).replace('.', ',')));
					$('.single_add_to_cart_button').text('<?php echo esc_js(__('Add to cart', 'woocommerce')); ?>');
				}
			});
		});
		</script>
		
		<style>
		.rental-toggle-wrapper label input[type="radio"] {
			appearance: none;
			width: 18px;
			height: 18px;
			border: 2px solid #000;
			border-radius: 50%;
			position: relative;
			cursor: pointer;
		}
		.rental-toggle-wrapper label input[type="radio"]:checked::after {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 10px;
			height: 10px;
			background: currentColor;
			border-radius: 50%;
		}
		.rental-toggle-wrapper label:has(input:checked) {
			background: #000 !important;
			color: #fff !important;
		}
		.rental-toggle-wrapper label:has(input:checked) span {
			color: #fff !important;
		}
		</style>
		<?php
	}
}
add_action('woocommerce_before_add_to_cart_button', 'marsey_add_rental_option_to_product', 5);

// Add rental info to cart item
function marsey_add_rental_to_cart_item_data($cart_item_data, $product_id) {
	if (isset($_POST['is_rental']) && $_POST['is_rental'] == '1') {
		$cart_item_data['is_rental'] = true;
		$rental_price = get_post_meta($product_id, '_rental_price', true);
		if (!empty($rental_price)) {
			$cart_item_data['rental_price'] = $rental_price;
		}
	}
	return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'marsey_add_rental_to_cart_item_data', 10, 2);

// Display rental info in cart
function marsey_display_rental_in_cart($item_data, $cart_item) {
	if (isset($cart_item['is_rental']) && $cart_item['is_rental']) {
		$item_data[] = array(
			'name' => __('Type', 'goya'),
			'value' => __('Rental', 'goya')
		);
	}
	return $item_data;
}
add_filter('woocommerce_get_item_data', 'marsey_display_rental_in_cart', 10, 2);

// Modify cart item price for rentals
function marsey_modify_cart_item_price($cart_object) {
	foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
		if (isset($cart_item['is_rental']) && $cart_item['is_rental'] && isset($cart_item['rental_price'])) {
			$cart_item['data']->set_price($cart_item['rental_price']);
		}
	}
}
add_action('woocommerce_before_calculate_totals', 'marsey_modify_cart_item_price', 10, 1);

// Add rental info to order meta
function marsey_add_rental_to_order_meta($item, $cart_item_key, $values, $order) {
	if (isset($values['is_rental']) && $values['is_rental']) {
		$item->add_meta_data(__('Type', 'goya'), __('Rental', 'goya'));
	}
}
add_action('woocommerce_checkout_create_order_line_item', 'marsey_add_rental_to_order_meta', 10, 4);