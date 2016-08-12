<?php
/**
 * Load assets
 *
 * @author      Bas Elbers
 * @category    Admin
 * @package     BE_WooCommerce_PDF_Invoices/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BEWPI_Admin_Assets' ) ) :

/**
 * BEWPI_Admin_Assets Class.
 */
class BEWPI_Admin_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		global $wp_scripts;

		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';
		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';

		// Register admin styles
		wp_register_style( 'bewpi_admin_styles', BEWPI()->plugin_url() . '/assets/css/admin.css', array(), BEWPI_VERSION );
		wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );

		// Admin styles for BEWPI pages only
		if ( in_array( $screen_id, bewpi_get_screen_ids() ) ) {
			wp_enqueue_script( 'iris' );
			wp_enqueue_style( 'bewpi_admin_styles' );
			wp_enqueue_style( 'jquery-ui-style' );
			wp_enqueue_style( 'wp-color-picker' );
		}
	}


	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script( 'bewpi_admin', BEWPI()->plugin_url() . '/assets/js/admin/bewpi_admin' . $suffix . '.js', array( 'jquery', 'jquery-ui-core', 'jquery-tiptip' ), BEWPI_VERSION );
		wp_register_script( 'jquery-tiptip', BEWPI()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), BEWPI_VERSION, true );

		// WooCommerce admin pages
		if ( in_array( $screen_id, bewpi_get_screen_ids() ) ) {
			wp_enqueue_script( 'bewpi_admin' );
		}
	}
}

endif;

return new BEWPI_Admin_Assets();