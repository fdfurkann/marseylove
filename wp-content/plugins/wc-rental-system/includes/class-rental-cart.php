<?php
class Rental_Cart {
	public function __construct() {
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_rental_data_to_cart' ), 10, 3 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_rental_data_in_cart' ), 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'update_rental_price' ), 10, 1 );
	}

	public function add_rental_data_to_cart( $cart_item_data, $product_id, $variation_id ) {
		if ( isset( $_POST['rental_start_date'] ) && isset( $_POST['rental_end_date'] ) ) {
			$cart_item_data['rental_start'] = sanitize_text_field( $_POST['rental_start_date'] );
			$cart_item_data['rental_end']   = sanitize_text_field( $_POST['rental_end_date'] );

			$start = new DateTime( $cart_item_data['rental_start'] );
			$end   = new DateTime( $cart_item_data['rental_end'] );
			$days  = $start->diff($end)->days + 1;
			$cart_item_data['rental_days'] = $days;
		}
		return $cart_item_data;
	}

	public function display_rental_data_in_cart( $item_data, $cart_item ) {
		if ( isset( $cart_item['rental_start'] ) ) {
			$item_data[] = array( 'key' => 'Başlangıç', 'value' => $cart_item['rental_start'] );
			$item_data[] = array( 'key' => 'Bitiş', 'value' => $cart_item['rental_end'] );
			$item_data[] = array( 'key' => 'Gün', 'value' => $cart_item['rental_days'] );
		}
		return $item_data;
	}

	public function update_rental_price( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

		foreach ( $cart->get_cart() as $cart_item ) {
			if ( isset( $cart_item['rental_days'] ) ) {
				$daily_price = get_post_meta( $cart_item['product_id'], '_rental_daily_price', true );
				$deposit     = get_post_meta( $cart_item['product_id'], '_rental_deposit', true );
				$total_price = ( $daily_price * $cart_item['rental_days'] ) + (float)$deposit;
				$cart_item['data']->set_price( $total_price );
			}
		}
	}
}
