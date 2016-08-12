<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Templates Controller
 *
 * WooCommerce PDF Invoices Templates Class which handles templates. This class loads in available templates.
 *
 * @class 		BEWPI_Templates
 * @version		1.0.0
 * @package		BE_WooCommerce_PDF_Invoices/Classes/Templates
 * @category	Class
 * @author 		Bas Elbers
 */
class BEWPI_Templates {

	/** @var array Array of template classes */
	public $templates;

	/** @var BEWPI_Templates The single instance of the class */
	protected static $_instance = null;

	/**
	 * Main BEWPI_Templates Instance.
	 *
	 * Ensures only one instance of BEWPI_Templates is loaded or can be loaded.
	 *
	 * @since 3.0.0
	 * @static
	 * @return BEWPI_Templates Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 3.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 3.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.1' );
	}

	/**
	 * Constructor for the email class hooks in all emails that can be sent.
	 *
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init email classes.
	 */
	public function init() {
		// Include email classes
		include_once( 'templates/class-bewpi-template.php' );

		$this->templates['BEWPI_Template_Invoice']              = include( 'templates/class-bewpi-template-invoice.php' );

		$this->templates = apply_filters( 'bewpi_template_classes', $this->templates );
	}

	/**
	 * Return the template classes - used in admin to load settings.
	 *
	 * @return array
	 */
	public function get_templates() {
		return $this->templates;
	}
}