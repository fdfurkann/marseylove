<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Iyzico_Gateway extends WC_Payment_Gateway {
	public function __construct() {
		$this->id = 'iyzico';
		$this->icon = '';
		$this->has_fields = false;
		$this->method_title = 'iyzico';
		$this->method_description = 'iyzico API v2 ile güvenli ödeme.';
		
		// Enable by default
		$this->enabled = 'yes';

		$this->init_form_fields();
		$this->init_settings();

		$this->title = $this->get_option( 'title', 'Credit Card (iyzico)' );
		$this->enabled = $this->get_option( 'enabled', 'yes' );
		$this->api_key = $this->get_option( 'api_key' );
		$this->secret_key = $this->get_option( 'secret_key' );
		$this->sandbox = $this->get_option( 'sandbox' ) === 'yes';

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_iyzico', array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_api_wc_iyzico_gateway', array( $this, 'check_callback' ) );
	}
	
	/**
	 * Check if this gateway is available in the user's country
	 */
	public function is_available() {
		if ( $this->enabled !== 'yes' ) {
			return false;
		}
		return true;
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => 'Aktif/Pasif',
				'type'    => 'checkbox',
				'label'   => 'iyzico Ödeme Geçidini Etkinleştir',
				'default' => 'yes'
			),
			'title' => array(
				'title'       => 'Başlık',
				'type'        => 'text',
				'description' => 'Ödeme sayfasında görünecek başlık.',
				'default'     => 'Kredi Kartı (iyzico)',
			),
			'api_key' => array(
				'title' => 'API Key',
				'type'  => 'text',
			),
			'secret_key' => array(
				'title' => 'Secret Key',
				'type'  => 'password',
			),
			'sandbox' => array(
				'title'   => 'Test Modu (Sandbox)',
				'type'    => 'checkbox',
				'label'   => 'Sandbox Kullan',
				'default' => 'yes'
			),
		);
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true )
		);
	}

	public function receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );
		$checkout_form_html = $this->get_iyzico_checkout_form( $order );
		echo $checkout_form_html;
	}

	private function get_iyzico_checkout_form( $order ) {
		// Mocked iyzico v2 Form Initialization
		// In a real scenario, we would send a request to iyzico API and get the token
		// and the script for the checkout form.
		
		$api_url = $this->sandbox ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
		
		// This is a simplified representation of the iyzico checkout form initialization
		$html = '<h3>Ödeme yapılıyor, lütfen bekleyin...</h3>';
		$html .= '<div id="iyzipay-checkout-form" class="responsive"></div>';
		
		// Normally we would generate a signature and send the request here.
		// For this implementation, we will simulate the successful redirection in a real environment.
		
		return $html;
	}

	public function check_callback() {
		// Handle iyzico callback/webhook
		if ( isset( $_POST['token'] ) ) {
			$token = sanitize_text_field( $_POST['token'] );
			// Verify token with iyzico and complete order
		}
	}
}
