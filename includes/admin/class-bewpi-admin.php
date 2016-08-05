<?php
/**
 * WooCommerce PDF Invoices Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * BEWPI_Admin Class
 */
class BEWPI_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	//	add_action( 'current_screen', array( $this, 'conditional_includes' ) );
	//	add_action( 'admin_init', array( $this, 'buffer' ), 1 );
	//	add_action( 'admin_init', array( $this, 'preview_emails' ) );
	//	add_action( 'admin_init', array( $this, 'prevent_admin_access' ) );
	//	add_action( 'admin_init', array( $this, 'admin_redirects' ) );
	//	add_action( 'admin_footer', 'wc_print_js', 25 );
	//	add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once( 'bewpi-admin-functions.php' );
		include_once( 'class-bewpi-admin-menus.php' );
		include_once( 'class-bewpi-admin-notices.php' );
		include_once( 'class-bewpi-admin-assets.php' );
	}
}

return new BEWPI_Admin();