<?php
/**
 * Plugin Name:       WooCommerce PDF Invoices
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-pdf-invoices
 * Description:       Automatically generate and attach customizable PDF Invoices to WooCommerce emails and connect with Dropbox, Google Drive, OneDrive or Egnyte.
 * Version:           3.0.0
 * Author:            Bas Elbers
 * Author URI:        http://wcpdfinvoices.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-pdf-invoices
 * Domain Path:       /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'BE_WooCommerce_PDF_Invoices' ) ) :

/**
 * Main BE_WooCommerce_PDF_Invoices Class
 *
 * @class BE_WooCommerce_PDF_Invoices
 */
final class BE_WooCommerce_PDF_Invoices {

	/**
	 * WooCommerce PDF Invoices version.
	 *
	 * @var string
	 */
	public $version = '3.0.0';

	/**
	 * The single instance of the class.
	 *
	 * @var BE_WooCommerce_PDF_Invoices
	 * @since 3.0
	 */
	protected static $_instance = null;

	/**
	 * Main BE_WooCommerce_PDF_Invoices Instance.
	 *
	 * Ensures only one instance of BE_WooCommerce_PDF_Invoices is loaded or can be loaded.
	 *
	 * @since 3.0.0
	 * @static
	 * @see BEWPI()
	 * @return BE_WooCommerce_PDF_Invoices - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * BE_WooCommerce_PDF_Invoices Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'bewpi_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 * @since  2.3
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( 'BEWPI_Install', 'install' ) );
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		add_action( 'init', array( $this, 'init' ), 0 );
		//add_action( 'init', array( 'WC_Shortcodes', 'init' ) );
	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {
		$this->define( 'BEWPI_PLUGIN_FILE', __FILE__ );
		$this->define( 'BEWPI_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'BEWPI_DIR', dirname( __FILE__ ) );
		$this->define( 'BEWPI_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		include_once( 'includes/bewpi-core-functions.php' );
		include_once( 'includes/class-bewpi-install.php' );
		include_once( 'includes/class-bewpi-ajax.php' );

		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-bewpi-admin.php' );
		}

//		include_once( 'includes/abstracts/abstract-bewpi-document.php' );
//		include_once( 'includes/abstracts/abstract-bewpi-invoice.php' );
//		include_once( 'includes/abstracts/abstract-bewpi-setting.php' );
//		include_once( 'includes/class-bewpi-invoice.php' );
	}

	/**
	 * Function used to Init WooCommerce PDF Invoices Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		include_once( 'includes/bewpi-template-functions.php' );
	}

	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Before init action.
		do_action( 'before_bewpi_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Load class instances.
		//$this->invoice_factory = new BEWPI_Invoice_Factory();                   // Invoice Factory to create new invoice instances

		// Init action.
		do_action( 'bewpi_init' );
	}

	/**
	 * Load Localisation files.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woocommerce-pdf-invoices',
			false,
			apply_filters( 'bewpi_lang_dir', basename( dirname( __FILE__ ) ) . '/lang' ) );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'bewpi_template_path', 'bewpi/' );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}
}

endif;

/**
 * Main instance of BE_WooCommerce_PDF_Invoices.
 *
 * Returns the main instance of BEWPI
 *
 * @since  3.0.0
 * @return BE_WooCommerce_PDF_Invoices
 */
function BEWPI() {
	return BE_WooCommerce_PDF_Invoices::instance();
}

// Global for backwards compatibility.
$GLOBALS['bewpi'] = BEWPI();