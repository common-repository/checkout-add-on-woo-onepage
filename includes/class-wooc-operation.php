<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ACL_WOOC_Operation {

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct () {
       add_filter( 'acl_woosc_checkout', array($this,'checkout_callback') );
       //Remove coupon
       add_action('wp_ajax_wooc_remove_coupon', array($this,'remove_coupon'));
       add_action('wp_ajax_nopriv_wooc_remove_coupon', array($this,'remove_coupon'));
       //Update Checkout page
        add_action('wp_ajax_wooc_update_checkout', array($this,'update_checkout'));
        add_action('wp_ajax_nopriv_wooc_update_checkout', array($this,'update_checkout'));

    }
    public function checkout_callback($woosc_checkout){
        $woosc_checkout=do_shortcode( '[woocommerce_checkout]');
        return $woosc_checkout;
    }
    public function remove_coupon()
    {
        global $woocommerce;
        $result=$woocommerce->cart->remove_coupon( filter_input( INPUT_POST, 'coupon' ) );
        echo wp_send_json($result);
        wp_die();
    }
    public function update_checkout(){
        if ( !defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
            define( 'WOOCOMMERCE_CHECKOUT', true );
        }
        // generate and calculate shipping items
        global $woocommerce;
        $woocommerce->cart->calculate_totals();
        $woocommerce->cart->calculate_shipping();

        if ( ob_get_length() > 0 ) {

            ob_clean();
        }
        ob_start();
        wc_get_template( 'checkout.php', array(), '', trailingslashit( ACL_WOOC_PATH.'templates/' ) );
        $template = ob_get_clean();

        $to_send = array(
            'data' => $template,
            'order_review_nonce' => wp_create_nonce( 'update-order-review' )
        );
        wp_send_json($to_send);
    }

}
new ACL_WOOC_Operation();
