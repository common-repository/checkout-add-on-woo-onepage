<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ACL_WOOC_Admin {

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct () {
        //For Future use.
    }
}
//Exporting admin settings to Woosc plugin
if ( is_admin()) {
    new ACL_WOOC_Admin();
}