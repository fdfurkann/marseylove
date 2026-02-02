<?php
class Rental_Order {
	public function __construct() {
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_rental_data_to_order' ), 10, 4 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'mark_days_as_booked' ) );
	}

	public function save_rental_data_to_order( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['rental_start'] ) ) {
			$item->add_meta_data( 'Rental Start', $values['rental_start'] );
			$item->add_meta_data( 'Rental End', $values['rental_end'] );
			$item->add_meta_data( 'Rental Days', $values['rental_days'] );
		}
	}

	public function mark_days_as_booked( $order_id ) {
		global $wpdb;
		$order = wc_get_order( $order_id );
		$table_name = $wpdb->prefix . 'wc_rental_availability';

		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			$start_date = $item->get_meta( 'Rental Start' );
			$end_date   = $item->get_meta( 'Rental End' );

			if ( $start_date && $end_date ) {
				$begin = new DateTime( $start_date );
				$end   = new DateTime( $end_date );
				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($begin, $interval ,$end->modify('+1 day'));

				foreach($daterange as $date){
					$wpdb->insert( $table_name, array(
						'product_id'  => $product_id,
						'rental_date' => $date->format("Y-m-d"),
						'status'      => 'booked',
						'order_id'    => $order_id
					) );
				}
			}
		}
	}
}
