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
		$this->label = __( 'General', 'woocommerce-pdf-invoices' );

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

		$settings = apply_filters( 'woocommerce_general_settings', array(

			array( 'title' => __( 'General Options', 'woocommerce' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'   => __( 'Enable Taxes', 'woocommerce' ),
				'desc'    => __( 'Enable taxes and tax calculations', 'woocommerce' ),
				'id'      => 'woocommerce_calc_taxes',
				'default' => 'no',
				'type'    => 'checkbox'
			),

			array(
				'title'   => __( 'Store Notice', 'woocommerce' ),
				'desc'    => __( 'Enable site-wide store notice text', 'woocommerce' ),
				'id'      => 'woocommerce_demo_store',
				'default' => 'no',
				'type'    => 'checkbox'
			),

			array(
				'title'    => __( 'Store Notice Text', 'woocommerce' ),
				'desc'     => '',
				'id'       => 'woocommerce_demo_store_notice',
				'default'  => __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'woocommerce' ),
				'type'     => 'textarea',
				'css'     => 'width:350px; height: 65px;',
				'autoload' => false
			),

			array( 'type' => 'sectionend', 'id' => 'general_options'),

			array( 'title' => __( 'Currency Options', 'woocommerce' ), 'type' => 'title', 'desc' => __( 'The following options affect how prices are displayed on the frontend.', 'woocommerce' ), 'id' => 'pricing_options' ),

			array(
				'title'    => __( 'Thousand Separator', 'woocommerce' ),
				'desc'     => __( 'This sets the thousand separator of displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_thousand_sep',
				'css'      => 'width:50px;',
				'default'  => ',',
				'type'     => 'text',
				'desc_tip' =>  true,
			),

			array(
				'title'    => __( 'Decimal Separator', 'woocommerce' ),
				'desc'     => __( 'This sets the decimal separator of displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_decimal_sep',
				'css'      => 'width:50px;',
				'default'  => '.',
				'type'     => 'text',
				'desc_tip' =>  true,
			),

			array(
				'title'    => __( 'Number of Decimals', 'woocommerce' ),
				'desc'     => __( 'This sets the number of decimal points shown in displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_num_decimals',
				'css'      => 'width:50px;',
				'default'  => '2',
				'desc_tip' =>  true,
				'type'     => 'number',
				'custom_attributes' => array(
					'min'  => 0,
					'step' => 1
				)
			),

			array( 'type' => 'sectionend', 'id' => 'pricing_options' )

		) );

		return apply_filters( 'bewpi_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a colour picker input box.
	 *
	 * @param mixed $name
	 * @param string $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
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
