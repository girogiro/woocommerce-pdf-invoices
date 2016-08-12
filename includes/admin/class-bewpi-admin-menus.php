<?php
/**
 * Setup menus in WP admin.
 *
 * @author   Bas Elbers
 * @category Admin
 * @package  BE_WooCommerce_PDF_Invoices/Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BEWPI_Admin_Menus' ) ) :

	/**
	 * WC_Admin_Menus Class.
	 */
	class BEWPI_Admin_Menus {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			// Add menus.
			add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );
		}

		/**
		 * Add menu item.
		 */
		public function settings_menu() {
			add_submenu_page( 'woocommerce', __( 'PDF Invoices', 'woocommerce-pdf-invoices' ), __( 'PDF Invoices', 'woocommerce-pdf-invoices' ), 'manage_woocommerce', 'bewpi-settings', array(
				$this,
				'settings_page',
			) );
		}

		/**
		 * Init the settings page.
		 */
		public function settings_page() {
			if ( ! class_exists( 'BEWPI_Admin_Settings' ) ) {
				include( 'class-bewpi-admin-settings.php' );
			}
			BEWPI_Admin_Settings::output();
		}
	}

endif;

return new BEWPI_Admin_Menus();