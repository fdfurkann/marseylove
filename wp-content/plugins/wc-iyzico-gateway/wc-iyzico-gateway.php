<?php
/**
 * Plugin Name: WC iyzico Payment Gateway
 * Description: WooCommerce için iyzico API v2 tabanlı ödeme geçidi.
 * Version: 1.0.0
 * Author: GitHub Copilot
 * Text Domain: wc-iyzico-gateway
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'WC_IYZICO_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_IYZICO_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', 'init_iyzico_gateway' );

function init_iyzico_gateway() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	require_once WC_IYZICO_PATH . 'includes/class-iyzico-gateway.php';

	add_filter( 'woocommerce_payment_gateways', 'add_iyzico_gateway_class' );
}

function add_iyzico_gateway_class( $methods ) {
	$methods[] = 'WC_Iyzico_Gateway';
	return $methods;
}
