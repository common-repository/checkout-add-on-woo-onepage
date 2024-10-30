<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ACL_WOOC_Plugin {

	/**
	 * The single instance of ACL_Woo_Onepage_Plugin.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version='1.0.0';

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token='acl_wooc';

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */

	public function __construct (  ) {
	    //Load requires and constant
        $this->assets_url=ACL_WOOC_URL.'assets';

        $this->define_constants();
        $this->includes();
        // Load frontend JS & CSS
       add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
       add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
        // Load admin JS & CSS
        if(isset($_GET['page']) && ($_GET['page']=="acl-wooc-license")){
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
            //add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

        }
        // Handle localisation
        $this->load_plugin_textdomain();
        add_action( 'init', array( $this, 'load_localisation' ), 0 );

	} // End __construct ()

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        /**
        * admin.
         */
        include_once('class-wooc-admin.php');
        /**
         * operations.
         */
        include_once('class-wooc-operation.php');
    }
    /**
     * Define WOOC Constants.
     */
    private function define_constants() {
        $this->define( 'ACL_WOOC_ABSPATH', dirname( ACL_WOOC_PLUGIN_FILE ) . '/' );
        $this->define( 'ACL_WOOC_PHP_VERSION', '5.4'  );
        $this->define( 'ACL_WOOC_WP_VERSION', '3.0'  );
    }
    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }
	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
	    wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . '/css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
	    global $woocommerce;
	    //Country & State
        wp_register_script( $this->_token . '-country-select', esc_url( $this->assets_url ) . '/js/country-select-min.js', array( 'jquery' ), $this->_version );
        wp_enqueue_script( $this->_token . '-country-select' );
        wp_localize_script( $this->_token .'_country-select', 'ops_country_select_params', array(
            'countries'              => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
            'i18n_select_state_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' ),
        ) );
        wp_enqueue_script( 'wc-address-i18n' );
        //Front Checkout
        $checkout_data = array(
            'wc_old_version' => version_compare( $woocommerce->version, 2.1, '<' ),
            'update_order_review_nonce' => wp_create_nonce( "update-order-review" ),
            'apply_coupon_nonce' => wp_create_nonce( "apply-coupon" ),
            'ajax_url' => '',
            'ajax_loader_url' => '',
            'option_guest_checkout' => get_option( 'woocommerce_enable_guest_checkout' )
        );
        if ( function_exists( 'WC' ) ) {
            $checkout_data['ajax_url'] = WC()->ajax_url();
            $checkout_data['ajax_loader_url'] = apply_filters( 'woocommerce_ajax_loader_url', str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/images/ajax-loader@2x.gif' );
        }
        wp_register_script( $this->_token . '-front-checkout', esc_url( $this->assets_url ) . '/js/front-checkout-min.js', array( 'jquery' ), $this->_version );
        wp_enqueue_script( $this->_token . '-front-checkout' );
        wp_localize_script( $this->_token . '-front-checkout', 'woosc_checkout_data', $checkout_data );

        //custom front
	    wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . '/js/frontend.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );

        wp_localize_script($this->_token . '-frontend', 'wooc_ajax_object',
            array(
                'ajax_url' => strtok(admin_url('admin-ajax.php'), '?'),
                'admin_url' => strtok(admin_url(), '?'),
                )
        );

	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
	    wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . '/js/admin.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
		wp_localize_script($this->_token . '-admin', 'wooc_admin_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
            )
        );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'woo-one-page-checkout', false, ACL_WOOC_ABSPATH . 'lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'woo-one-page-templates';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, ACL_WOOC_ABSPATH . 'lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main wooc Instance
	 *
	 * Ensures only one instance of wooc is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WordPress_Plugin_Template()
	 * @return Main WordPress_Plugin_Template instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

    /**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}