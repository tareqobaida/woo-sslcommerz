<?php
/**
 * Generate SSLCommerz button link
 **/
add_action( 'woocommerce_new_order', 'ssl_form',  1, 1  );
function ssl_form($order_id){
	echo "<h1>hi</h1>";
}
//  function generate_SSLCommerz_form($order_id){
// 	global $woocommerce;
// 	$order = new WC_Order($order_id);
// 	$order_id = $order_id.'_'.date("ymds");
// // 	$redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
// 	$fail_url = ($this -> fail_page_id=="" || $this -> fail_page_id==0)?get_site_url() . "/":get_permalink($this -> fail_page_id);
// // 	$redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
// // 	$fail_url = add_query_arg( 'wc-api', get_class( $this ), $fail_url );
// 	$declineURL = $order->get_cancel_order_url();
// 	$SSLCommerz_args = array(
// 			'store_id'      => STORE_ID,
// 			'total_amount'           => $order -> order_total,
// 			'tran_id'         => $order_id,
// 			'success_url' => $redirect_url,
// 			'fail_url' => $declineURL,
// 			'cancel_url' => $declineURL,
// 			'cus_name'     => $order -> billing_first_name .' '. $order -> billing_last_name,
// 			'cus_add1'  => trim($order -> billing_address_1, ','),
// 			'cus_country'  => wc()->countries -> countries [$order -> billing_country],
// 			'cus_state'    => $order -> billing_state,
// 			'cus_city'     => $order -> billing_city,
// 			'cus_postcode'      => $order -> billing_postcode,
// 			'cus_phone'      => $order->billing_phone,
// 			'cus_email'    => $order -> billing_email,
// 			'ship_name'    => $order -> shipping_first_name .' '. $order -> shipping_last_name,
// 			'ship_add1' => $order -> shipping_address_1,
// 			'ship_country' => $order -> shipping_country,
// 			'ship_state'   => $order -> shipping_state,
// 			'ship_city'    => $order -> shipping_city,
// 			'ship_postcode'     => $order -> shipping_postcode,
// 			'language'         => 'EN',
// 			'currency'         => get_woocommerce_currency()
// 	);
// // 	var_dump($SSLCommerz_args);
// 	foreach($SSLCommerz_args as $param => $value) {
// 		$paramsJoined[] = "$param=$value";
// 	}
// 	$paramsJoined = array();
// 	foreach($SSLCommerz_args as $key => $value){
// 		$paramsJoined[] = "<input type='hidden' name='$key' value='$value'/>";
// 	}
// 	//   print_r($paramsJoined);exit;
// 	$SSLCommerz_args_array   = array();
// 	//$SSLCommerz_args_array[] = "<input type='hidden' name='encRequest' value='$encrypted_data'/>";
// 	//$SSLCommerz_args_array[] = "<input type='hidden' name='access_code' value='{$this->access_code}'/>";
// 	wc_enqueue_js( '
//     $.blockUI({
//         message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to SSLCommerz to make payment.', 'woocommerce' ) ) . '",
//         baseZ: 99999,
//         overlayCSS:
//         {
//             background: "#fff",
//             opacity: 0.6
//         },
//         css: {
//             padding:        "20px",
//             zindex:         "9999999",
//             textAlign:      "center",
//             color:          "#555",
//             border:         "3px solid #aaa",
//             backgroundColor:"#fff",
//             cursor:         "wait",
//             lineHeight:     "24px",
//         }
//     });
// jQuery("#submit_SSLCommerz_payment_form").click();
// ' );
// 	//jQuery("#submit_SSLCommerz_payment_form").click();
// // 	if ( 'yes' == $this->testmode ) {
// // 		$liveurl = $this->testurl ;
// // 	} else {
// // 		$liveurl = $this->liveurl ;
// // 	}
// 	$form = '<form action="' . esc_url( $liveurl ) . '" method="post" id="SSLCommerz_payment_form" target="_top">
// ' . implode( '', $paramsJoined ) . '
// <!-- Button Fallback -->
// <div class="payment_buttons">
// <input type="submit" class="button alt" id="submit_SSLCommerz_payment_form" value="' . __( 'Pay via SSLCommerz', 'woocommerce' ) . '" /> <a class="button cancel" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Cancel order &amp; restore cart', 'woocommerce' ) . '</a>
// </div>
// <script type="text/javascript">
// jQuery(".payment_buttons").hide();
// </script>
// </form>';
// 	return $form;
// }