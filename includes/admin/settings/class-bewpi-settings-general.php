<?php
/**
 * WooCommerce PDF Invoices General Settings
 *
 * @author      Bas Elbers
 * @category    Admin
 * @package     BE_WooCommerce_PDF_Invoices/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BEWPI_Settings_General' ) ) :

/**
 * BEWPI_Admin_Settings_General.
 */
class BEWPI_Settings_General extends BEWPI_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'woocommerce' );

		add_filter( 'bewpi_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'bewpi_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'bewpi_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters( 'bewpi_general_settings', array(

			// General Options
			array( 'title' => __( 'General Options', 'woocommerce-pdf-invoices' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'           => __( 'Attach to Email', 'woocommerce-pdf-invoices' ),
				'desc'            => __( 'Processing order', 'woocommerce-pdf-invoices' ),
				'id'              => 'bewpi_customer_processing_order',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'start',
				'show_if_checked' => 'option',
			),

			array(
				'desc'            => __( 'Completed order', 'woocommerce-pdf-invoices' ),
				'id'              => 'bewpi_customer_completed_order',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => '',
				'show_if_checked' => 'yes',
				'autoload'        => false,
			),

			array(
				'desc'            => __( 'Customer invoice', 'woocommerce-pdf-invoices' ),
				'id'              => 'bewpi_customer_invoice',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => '',
				'show_if_checked' => 'yes',
				'autoload'        => false,
			),

			array(
				'desc'            => __( 'New Order', 'woocommerce-pdf-invoices' ),
				'id'              => 'bewpi_new_order',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'end',
				'show_if_checked' => 'yes',
				'autoload'        => false,
			),

			array( 'type' => 'sectionend', 'id' => 'general_options'),

			// Download Options
			array( 'title' => __( 'Download Options', 'woocommerce-pdf-invoices' ), 'type' => 'title', 'desc' => '', 'id' => 'download_options' ),

			array(
				'title'    => __( 'View PDF', 'woocommerce-pdf-invoices' ),
				'desc'     => __( 'How would you like to view the PDF invoice?', 'woocommerce-pdf-invoices' ),
				'id'       => 'bewpi_view_pdf',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width:300px;',
				'default'  => 'browser',
				'type'     => 'select',
				'options'  => array(
					'download'  => __( 'Download', 'woocommerce-pdf-invoices' ),
					'browser'   => __( 'Open in new browser tab/window', 'woocommerce-pdf-invoices' ),
				),
				'desc_tip' =>  true,
			),

			array(
				'title'         => __( 'My Account Page', 'woocommerce-pdf-invoices' ),
				'desc'          => __( 'Enable download invoice on "My Account" page', 'woocommerce-pdf-invoices' ),
				'id'            => 'bewpi_download_invoice_account',
				'default'       => 'no',
				'type'          => 'checkbox'
			),

			array( 'type' => 'sectionend', 'id' => 'download_options' ),

			// Cloud Options
			array(
				'title' => __( 'Cloud Options', 'woocommerce-pdf-invoices' ),
				'type' => 'title',
				'desc' => sprintf( __( 'Sign up on %s and enter your account below to automatically send invoices to your Dropbox, OneDrive, Google Drive or Egnyte.', 'woocommerce-pdf-invoices' ), '<a href="https://emailitin.com">Email It In</a>' ),
				'id' => 'cloud_options'
			),

			array(
				'title'         => __( 'Cloud Storage', 'woocommerce-pdf-invoices' ),
				'desc'          => __( 'Enable Email It In', 'woocommerce-pdf-invoices' ),
				'id'            => 'bewpi_email_it_in',
				'default'       => 'no',
				'type'          => 'checkbox'
			),

			array(
				'title'    => __( 'User Account Email', 'woocommerce-pdf-invoices' ),
				'desc'     => sprintf( __( 'Enter your user account email from %s.', 'woocommerce-pdf-invoices' ), 'Email It In' ),
				'id'       => 'bewpi_email_it_in_account',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'desc_tip' =>  true,
			),

			array( 'type' => 'sectionend', 'id' => 'cloud_options' ),

		) );

		return apply_filters( 'bewpi_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		BEWPI_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new BEWPI_Settings_General();
