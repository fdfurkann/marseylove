<?php
class Rental_Frontend {
	public function __construct() {
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_booking_form' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
	}

	public function enqueue_frontend_assets() {
		if ( is_product() ) {
			wp_enqueue_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'rental-frontend-css', WC_RENTAL_URL . 'assets/css/rental-frontend.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'rental-frontend-js', WC_RENTAL_URL . 'assets/js/rental-frontend.js', array( 'jquery' ), '1.0.0', true );

			global $post;
			wp_localize_script( 'rental-frontend-js', 'rentalVars', array(
				'dailyPrice' => get_post_meta( $post->ID, '_rental_daily_price', true ),
				'deposit'    => get_post_meta( $post->ID, '_rental_deposit', true ),
			) );
		}
	}

	public function display_booking_form() {
		global $product;
		if ( ! $product || $product->get_type() !== 'rental' ) return;

		include WC_RENTAL_PATH . 'templates/booking-form.php';
	}
}
