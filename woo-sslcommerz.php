<?php
if (! defined ( 'ABSPATH' )) {
	exit (); // Exit if accessed directly
}
/**
 * Plugin Name: Woo-sslcommerz
 * Plugin URI: https://github.com/tareqobaida/woo-sslcommerz
 * Description: SSLCommerz payment gateway integration with woocommerce. Inspired by and modified built in paypal gateway
 * Version: 1.0
 * Author: Abu Obaida
 * Author URI: http://tareqobaida.github.io
 * License: GPL2
 */
define ( 'STORE_ID', 'test_parmeeda001' );

add_action ( 'plugins_loaded', 'ssl_plugin_init', 0 );
function ssl_plugin_init() {
	if (! class_exists ( 'WC_Payment_Gateway' ))
		return;
	
/**
 * WC_Gateway_SSL Class.
 */
include_once 'class-wc-gateway-ssl.php';
	/**
	 * Add the Gateway to WooCommerce
	 */
	function add_ssl_gateway($methods) {
		$methods [] = 'WC_Gateway_SSL';
		return $methods;
	}
	
	add_filter ( 'woocommerce_payment_gateways', 'add_ssl_gateway' );
}


