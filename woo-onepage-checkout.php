<?php
/*
 * Plugin Name:Checkout Add-on for Woo OnePage - Lite
 * Version: 0.9
 * Plugin URI: https://amadercode.com/premium-products/woocommerce-one-page-checkout-shop
 * Description: Instant/Quick/OnePage Checkout Add-on - Lite for Woo OnePage Checkout Shop.
 * Author: AmaderCode Lab
 * Author URI: http://www.amadercode.com/
 * Requires at least: 4.0
 * Tested up to: 5.2
 * WC tested up to: 3.8
 * WC requires at least: 3.0
 * Text Domain: woo-one-page-checkout
 * Domain Path: /lang/
 * @package WordPress
 */

// Define ACL_WOOC_PLUGIN_FILE && ACL_WOOC_URL
if ( ! defined( 'ACL_WOOC_PLUGIN_FILE' ) ) {
    define( 'ACL_WOOC_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'ACL_WOOC_URL' ) ) {
    define( 'ACL_WOOC_URL', plugin_dir_url(__FILE__ ));
}
if ( ! defined( 'ACL_WOOC_PHP_VERSION' ) ) {
    define( 'ACL_WOOC_PHP_VERSION', '5.6');
}
if ( ! defined( 'ACL_WOOC_WP_VERSION' ) ) {
    define( 'ACL_WOOC_WP_VERSION', '4.0');
}
if ( ! defined( 'ACL_WOOC_PATH' ) ) {
    define( 'ACL_WOOC_PATH', plugin_dir_path(__FILE__ ));
}

if ( ! defined( 'ACL_WOOC_VERSION' ) ) {
    define( 'ACL_WOOC_VERSION', '1.0.0');
}

// Include the main Template plugin class.
if ( ! class_exists( 'ACL_WOOC_Plugin' ) ) {
    /**
     * Loading Add-on
     */
    include_once('includes/class-wooc-plugin.php');
    /**
     * install.
     */
    include_once('includes/class-wooc-install.php');
}

/**
 * Main instance of ACL_WOOC_Plugin.
 * @since  1.0.0
 * @return ACL_WOOC_Plugin
 */
function acl_wooc_plugin() {
    return ACL_WOOC_Plugin::instance();
}
add_action('init', 'acl_wooc_plugin');
//install hook
//