<?php
class Rental_Admin {
	public function __construct() {
		add_action( 'woocommerce_product_data_tabs', array( $this, 'rental_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'rental_panel' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_rental_data' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	public function rental_tab( $tabs ) {
		$tabs['rental'] = array(
			'label'    => __( 'Rental Settings', 'wc-rental-system' ),
			'target'   => 'rental_options',
			'class'    => array( 'show_if_rental' ),
			'priority' => 21,
		);
		return $tabs;
	}

	public function rental_panel() {
		echo '<div id="rental_options" class="panel woocommerce_options_panel hidden">';
		
		woocommerce_wp_text_input( array(
			'id'          => '_rental_daily_price',
			'label'       => __( 'Daily Price', 'wc-rental-system' ),
			'description' => __( 'Set the daily rental cost.', 'wc-rental-system' ),
			'type'        => 'number',
			'custom_attributes' => array( 'step' => 'any', 'min' => '0' )
		) );

		woocommerce_wp_text_input( array(
			'id'          => '_rental_deposit',
			'label'       => __( 'Security Deposit', 'wc-rental-system' ),
			'description' => __( 'Fixed deposit amount required.', 'wc-rental-system' ),
			'type'        => 'number',
		) );

		echo '</div>';
	}

	public function save_rental_data( $post_id ) {
		$daily_price = isset( $_POST['_rental_daily_price'] ) ? sanitize_text_field( $_POST['_rental_daily_price'] ) : '';
		$deposit = isset( $_POST['_rental_deposit'] ) ? sanitize_text_field( $_POST['_rental_deposit'] ) : '';

		update_post_meta( $post_id, '_rental_daily_price', $daily_price );
		update_post_meta( $post_id, '_rental_deposit', $deposit );
		update_post_meta( $post_id, '_price', $daily_price ); // For sorting/display
	}

	public function enqueue_admin_assets() {
		wp_enqueue_style( 'wc-rental-admin-css', WC_RENTAL_URL . 'assets/css/rental-admin.css' );
	}
}
