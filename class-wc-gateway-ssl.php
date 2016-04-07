<?php 
class WC_Gateway_SSL extends WC_Payment_Gateway {

	/** @var bool Whether or not logging is enabled */
	public static $log_enabled = false;

	/** @var WC_Logger Logger instance */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'ssl';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Proceed to SSLCOMMERZ', 'woocommerce' );
		$this->method_title       = __( 'SSLCOMMERZ', 'woocommerce' );
		$this->method_description = sprintf( __( 'PayPal standard sends customers to PayPal to enter their payment information. PayPal IPN requires fsockopen/cURL support to update order statuses after payment. Check the %ssystem status%s page for more details.', 'woocommerce' ), '<a href="' . admin_url( 'admin.php?page=wc-status' ) . '">', '</a>' );
		$this->supports           = array(
			'products',
			'refunds'
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
		$this->debug          = 'yes' === $this->get_option( 'debug', 'no' );
		$this->email          = $this->get_option( 'email' );
		$this->receiver_email = $this->get_option( 'receiver_email', $this->email );
		$this->identity_token = $this->get_option( 'identity_token' );

		self::$log_enabled    = $this->debug;

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
		} else {
			include_once( 'includes/class-wc-gateway-ssl-ipn-handler.php' );
			new WC_Gateway_Paypal_IPN_Handler( $this->testmode, $this->receiver_email );

			if ( $this->identity_token ) {
				include_once( 'includes/class-wc-gateway-ssl-pdt-handler.php' );
				new WC_Gateway_SSL_PDT_Handler( $this->testmode, $this->identity_token );
			}
		}
	}
	public function is_valid_for_use() {
		return true;
	}
	
	/**
	 * Admin Panel Options.
	 * - Options for bits like 'title' and availability on a country-by-country basis.
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
				<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'woocommerce' ); ?></strong>: <?php _e( 'PayPal does not support your store currency.', 'woocommerce' ); ?></p></div>
				<?php
			}
		}
	
		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {
			$this->form_fields = include( 'includes/settings-ssl.php' );
		}
		
		/**
		 * Process the payment and return the result.
		 * @param  int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
			include_once( 'includes/class-wc-gateway-ssl-request.php' );
	
			$order          = wc_get_order( $order_id );
			$paypal_request = new WC_Gateway_SSL_Request( $this );	
// 			generate_SSLCommerz_form($order_id);
			return array(
				'result'   => 'success',
				'redirect' => $order->get_checkout_payment_url( true )
			);
		}
		
}
	