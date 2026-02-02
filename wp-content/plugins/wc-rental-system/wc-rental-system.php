<?php
/**
 * Plugin Name: WC Rental System
 * Description: WooCommerce tabanlı kapsamlı kiralama sistemi. Günlük hesaplama, depozito ve müsaitlik takvimi.
 * Version: 1.0.0
 * Author: GitHub Copilot
 * Text Domain: wc-rental-system
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'WC_RENTAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_RENTAL_URL', plugin_dir_url( __FILE__ ) );

require_once WC_RENTAL_PATH . 'includes/class-rental-activator.php';

register_activation_hook( __FILE__, array( 'Rental_Activator', 'activate' ) );

function run_wc_rental_system() {
	if ( ! class_exists( 'WooCommerce' ) ) return;

	require_once WC_RENTAL_PATH . 'includes/class-rental-product-type.php';
	require_once WC_RENTAL_PATH . 'includes/class-rental-admin.php';
	require_once WC_RENTAL_PATH . 'includes/class-rental-frontend.php';
	require_once WC_RENTAL_PATH . 'includes/class-rental-cart.php';
	require_once WC_RENTAL_PATH . 'includes/class-rental-order.php';

	new Rental_Product_Type();
	new Rental_Admin();
	new Rental_Frontend();
	new Rental_Cart();
	new Rental_Order();
}
add_action( 'plugins_loaded', 'run_wc_rental_system' );
