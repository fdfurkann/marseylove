<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Product_Rental extends WC_Product {
	public function get_type() {
		return 'rental';
	}
}

class Rental_Product_Type {
	public function __construct() {
		add_filter( 'product_type_selector', array( $this, 'add_rental_type' ) );
		add_filter( 'woocommerce_product_class', array( $this, 'product_class_rental' ), 10, 2 );
	}

	public function add_rental_type( $types ) {
		$types['rental'] = __( 'Rental Product', 'wc-rental-system' );
		return $types;
	}

	public function product_class_rental( $classname, $product_type ) {
		if ( $product_type === 'rental' ) {
			return 'WC_Product_Rental';
		}
		return $classname;
	}
}
