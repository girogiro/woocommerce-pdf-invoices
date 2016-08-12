<?php
/**
 * Installation related functions and actions.
 *
 * @author   Bas Elbers
 * @category Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BEWPI_Install Class.
 */
class BEWPI_Install {

	public static function init() {
		add_filter( 'plugin_action_links_' . BEWPI_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'<a href="' . admin_url( 'admin.php?page=bewpi-settings' ) . '" title="' . esc_attr( __( 'View WooCommerce PDF Invoices Settings', 'woocommerce-pdf-invoices' ) ) . '">' . __( 'Settings', 'woocommerce-pdf-invoices' ) . '</a>'
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param	mixed $links Plugin Row Meta
	 * @param	mixed $file  Plugin Base file
	 * @return	array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( $file === BEWPI_PLUGIN_BASENAME ) {
			$row_meta = array(
				'support' => '<a href="' . esc_url( apply_filters( 'woocommerce_pdf_invoices_website_url', 'http://wcpdfinvoices.com' ) ) . '" title="' . esc_attr( __( 'Visit Premium Website', 'woocommerce-pdf-invoices' ) ) . '">' . __( 'Premium', 'woocommerce-pdf-invoices' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * Install WooCommerce PDF Invoices.
	 */
	public static function install() {
		// Ensure needed classes are loaded
		include_once( 'admin/class-bewpi-admin-notices.php' );

		self::create_options();
		self::create_files();

		BEWPI_Admin_Notices::remove_all_notices();
		BEWPI_Admin_Notices::add_notice( 'install' );

		// can't find 1 character of unexpected output
		ob_end_clean();
	}

	/**
	 * Default options.
	 *
	 * Sets up the default options used on the settings page.
	 */
	private static function create_options() {
		// Include settings so that we can run through defaults
		include_once( 'admin/class-bewpi-admin-settings.php' );

		$settings = BEWPI_Admin_Settings::get_settings_pages();

		foreach ( $settings as $section ) {
			if ( ! method_exists( $section, 'get_settings' ) ) {
				continue;
			}
			$subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

			foreach ( $subsections as $subsection ) {
				foreach ( $section->get_settings( $subsection ) as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}
	}

	/**
	 * Create files/directories.
	 */
	private static function create_files() {
		// Install files and folders for uploading files and prevent hotlinking
		$upload_dir      = wp_upload_dir();

		$files = array(
			array(
				'base' 		=> $upload_dir['basedir'] . '/bewpi_uploads/invoices',
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all'
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/bewpi_uploads/invoices',
				'file' 		=> 'index.html',
				'content' 	=> ''
			)
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}
}

BEWPI_Install::init();