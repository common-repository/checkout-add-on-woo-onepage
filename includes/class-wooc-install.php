<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//install
//register_activation_hook( ACL_WOOC_PLUGIN_FILE, 'acl_wooc_install');
function acl_wooc_install() {
    global $wp_version;
    if ( !class_exists('WooCommerce') ){
        wp_die('WooCommerce is required');
    }
    if ( version_compare( PHP_VERSION, ACL_WOOC_PHP_VERSION, '<' ) ) {
        wp_die('Minimum PHP Version required: ' . ACL_WOOC_PHP_VERSION );
    }
    if ( version_compare( $wp_version, ACL_WOOC_WP_VERSION, '<' ) ) {
        wp_die('Minimum Wordpress Version required: ' . ACL_WOOC_WP_VERSION );
    }
}
// Called when WooCommerce & Woo OnePage Checkout Shop is inactive to display an inactive notice.
 //@since 1.0
 //
add_action('plugins_loaded', 'acl_wooc_inactive_notice');
function acl_wooc_inactive_notice() {
    if ( current_user_can( 'activate_plugins' ) ) :
        if ( !class_exists('WooCommerce') ) :
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            deactivate_plugins('woo-onepage-checkout/woo-onepage-checkout.php');
            ?>
            <div id="message" class="error">
                <p><?php printf( __( '%sCheckout Add-on for Woo OnePage - Lite Checkout Shop is inactive.%s The %sWooCommerce plugin%s must be active for Checkout Add-on for Woo OnePage to work. Please %sinstall & activate WooCommerce%s', 'woo-one-page-templates' ), '<strong>', '</strong>', '<a href="http://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' . admin_url() . '/plugin-install.php?tab=plugin-information&plugin=woocommerce">', '&nbsp;&raquo;</a>' ); ?></p>
            </div>
        <?php elseif ( !class_exists('ACL_Woo_Onepage_Plugin')) :
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            deactivate_plugins('woo-onepage-checkout/woo-onepage-checkout.php');
            ?>
            <div id="message" class="error">
                <p><?php printf( __( '%sCheckout Add-on for Woo OnePage - Lite is inactive.%s The %sWoo OnePage Checkout Shop plugin%s must be active for Checkout Add-on for Woo OnePage to work. Please %sinstall & activate Woo OnePage Checkout Shop%s', 'woo-one-page-templates' ), '<strong>', '</strong>', '<a href="http://wordpress.org/plugins/woo-onepage/">', '</a>', '<a href="' . admin_url() . '/plugin-install.php?tab=plugin-information&plugin=woo-onepage">', '&nbsp;&raquo;</a>' ); ?></p>
            </div>
        <?php endif; ?>
    <?php endif;
}
//ACL wooc setting page redirection.
if(!function_exists('acl_wooc_settings_redirect')){
    function acl_wooc_settings_redirect( $plugin ) {
        if( $plugin == plugin_basename( ACL_WOOC_PLUGIN_FILE ) ) {
            exit(wp_redirect( admin_url('admin.php?page=woocommerce-one-page-checkout-shop')));
        }
    }
    add_action( 'activated_plugin', 'acl_wooc_settings_redirect' );

}
?>