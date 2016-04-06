<?php
/**
 * Plugin Name: Woosslcommerz
 * Plugin URI: http://remissionsoft.com
 * Description: Test Payment Gateway for WooCommerce That Always Pays Off
 * Version: 1.0
 * Author: sohel rana
 * Author URI: http://remissionsoft.com
 * License: GPL2
 */
add_action('plugins_loaded', 'woocommerce_tocka_sslcommerz_init', 0);
function woocommerce_tocka_sslcommerz_init() {
    if(!class_exists('WC_Payment_Gateway')) return;
    class WC_Tocka_sslcommerz extends WC_Payment_Gateway {
        public $store_id;
        public $tran_id;
        public function __construct( $fake=FALSE ){
            $this -> id = 'sslcommerz';
            $this -> method_title = 'sslcommerz';
            $this -> has_fields = FALSE;
            $this -> init_form_fields();
            $this -> init_settings();
            $this -> title = $this -> settings['title'];
            $this -> description = $this -> settings['description'];
            //sslcommerz auth
            $this->store_id = $this -> settings['store_id'];
            $this -> fake_url = str_replace( 'https:', 'http:', add_query_arg( array( 'wc-api' => 'WC_Tocka_sslcommerz_FakeRemote' ), home_url( '/' ) ) );
            add_action( 'init', array( $this, 'check_sslcommerz_response' ) );
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_receipt_sslcommerz', array( $this, 'receipt_page' ) );
            // Payment listener/API hook
            add_action( 'woocommerce_api_wc_tocka_sslcommerz', array( $this, 'check_ipn_response' ) );
            new WC_Tocka_sslcommerz_FakeRemote();
            // $callback_url = str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'WC_Tocka_sslcommerz', home_url( '/' ) ) );
            // $callback_url = "http://www.example.com/?wc-api=WC_Tocka_sslcommerz";
            // $callback_url = "http://www.example.com/wc-api/WC_Tocka_sslcommerz/";  // alternative
            if ( !$this->is_valid_for_use() ) $this->enabled = false;
            if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) $this->enabled = false;
        }
        /**
         * Check if this gateway should be forced disabled for some reason
         *
         * @access public
         * @return bool
         */
        function is_valid_for_use() {
            return true;
        }
        /**
         * Initialise Settings Form Fields
         *
         * Add an array of fields to be displayed
         * on the gateway's settings screen.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        function init_form_fields(){
            $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woosslcommerz'),
                    'type' => 'checkbox',
                    'label' => __('Enable sslcommerz Payment Module.', 'woosslcommerz'),
                    'default' => 'no'),
                'store_id' => array(
                    'title' => __('Store ID:', 'woosslcommerz'),
                    'type'=> 'text',
                    'description' => __('Store ID for sslcommerz auth.', 'woosslcommerz'),
                    'default' => __('sslcommerz', 'woosslcommerz')),
                'title' => array(
                    'title' => __('Title:', 'woosslcommerz'),
                    'type'=> 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woosslcommerz'),
                    'default' => __('sslcommerz', 'woosslcommerz')),
                'description' => array(
                    'title' => __('Description:', 'woosslcommerz'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woosslcommerz'),
                    'default' => __('Fake IPN Payment Gateway. It always pays off...', 'woosslcommerz')),                                    
            );
        }
        /**
         * Admin Options
         *
         * Setup the gateway settings screen.
         * Override this in your gateway.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function admin_options(){
            echo '<h3>'.__('sslcommerz Payment Gateway', 'woosslcommerz').'</h3>';
            echo '<p>'.__('sslcommerz service description sslcommerz service description sslcommerz service description.').'</p>';
            echo '<table class="form-table">';
            // Generate the HTML For the settings form.
            $this -> generate_settings_html();
            echo '</table>';
        }
        /**
         * Process Payment
         *
         * @access public
         * @param int $order_id Id of the order that's going to be processed
         * @return array
         */
        function process_payment($order_id){
            global $woocommerce;
            $order = new WC_Order( $order_id );
            $order->payment_complete();
            //$woocommerce->cart->empty_cart();  // Remove cart
            $woocommerce->session->sslcommerz_order_id = $order_id;
            return array(
                'result' => 'success',
                'redirect' => $this->fake_url,
            );
        }
        /**
         * Check IPN Response
         *
         * Check for valid sslcommerz server callback
         *
         * @since 1.0.0
         * @access public
         * @return void
         **/
        function check_ipn_response() {
            global $woocommerce;
            $order_id = $woocommerce->session->sslcommerz_order_id;  //@TODO pass order_id via $_GET
            $order = new WC_Order( $order_id );
            $order->add_order_note( __( 'sslcommerz IPN payment completed', 'woocommerce' ) );
            $order->payment_complete();
            wp_redirect( get_permalink( woocommerce_get_page_id( 'thanks' ) ) );
        }
    }
    class WC_Tocka_sslcommerz_FakeRemote {
        public function __construct() {
            $this -> ipn_url = str_replace( 'https:', 'http:', add_query_arg( array( 'wc-api' => 'WC_Tocka_sslcommerz' ), home_url( '/' ) ) );
            add_action( 'woocommerce_api_wc_tocka_sslcommerz_fakeremote', array( $this, 'fake_pg_page' ) );
        }
        public function fake_pg_page() {
            ob_end_clean();
            get_header();
            global $woocommerce;
            $order = new WC_Order($woocommerce->session->sslcommerz_order_id);  
            $store = new WC_Tocka_sslcommerz();
            $items = $order->get_items();
            ?>

                <div style="text-align: center;">
                    <h1>Woosslcommerz Payment Gateway.</h1>
                    <form id="payment_gw" name="payment_gw" method="POST" action="https://www.sslcommerz.com.bd/gwprocess/testbox/">
                        <input type="hidden" name="total_amount" value="<?php echo $order->order_total; ?>" />
                        <input type="hidden" name="store_id" value="<?php echo $store->store_id; ?>" />
                        <input type="hidden" name="tran_id" value="<?php echo $order->id; ?>" />
                        <input type="hidden" name="success_url" value="https://www.sslcommerz.com.bd/gwprocess/testbox/MerchantResponds/success.php" />
                        <input type="hidden" name="fail_url" value="https://www.sslcommerz.com.bd/gwprocess/testbox/MerchantResponds/fail.php" />
                        <input type="hidden" name="cancel_url" value="https://www.sslcommerz.com.bd/gwprocess/testbox/MerchantResponds/cancel.php" />   
                        <input type="hidden" name="version" value="2.00" /> 
                        <?php $i=0; foreach ($items as $key => $item): ?>
                            <input type="hidden" name="cart[<?php echo $i; ?>][product]" value="<?php echo $item['name']; ?>" />
                            <input type="hidden" name="cart[<?php echo $i; ?>][amount]" value="<?php echo $item['line_subtotal']; ?>" />                            
                        <?php $i++; endforeach ?>                        
                        <input type="submit" name="submit" value="Pay Now" />                   
                    </form>
                </div>
                <script type="text/javascript">
                    jQuery(function($){
                        
                            //window.location = 'https://www.sslcommerz.com.bd/testbox/process/index.php';
                            $('form#payment_gw').submit();
                        
                    });
                </script>
            <?php
            get_footer();
            ob_start();
        }
    }
    class WC_Tocka_sslcommerz_Fakeresponse {
        public function __construct() {
            $this -> ipn_url = str_replace( 'https:', 'http:', add_query_arg( array( 'wc-api' => 'WC_Tocka_sslcommerz' ), home_url( '/' ) ) );
            add_action( 'woocommerce_api_wc_tocka_sslcommerz_fakeresponse', array( $this, 'test' ) );
        }
        public function test() {
            ob_end_clean();
            get_header();
            global $woocommerce;
            $order = new WC_Order($woocommerce->session->sslcommerz_order_id);  
            $store = new WC_Tocka_sslcommerz();
            $items = $order->get_items();
            var_dump($_REQUEST);
            get_footer();
            ob_start();
        }
    }
    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_tocka_sslcommerz_gateway( $methods ) {
        $methods[] = 'WC_Tocka_sslcommerz';
        return $methods;
    }
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_tocka_sslcommerz_gateway' );
}
