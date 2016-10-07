<?php
/**
 * WooCommerce PDF Invoices Email Settings
 *
 * @author      Bas Elbers
 * @category    Admin
 * @package     BE_WooCommerce_PDF_Invoices/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BEWPI_Settings_Templates ' ) ) :

/**
 * BEWPI_Settings_Template.
 */
class BEWPI_Settings_Templates extends BEWPI_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'templates';
		$this->label = __( 'Templates', 'woocommerce' );

		add_filter( 'bewpi_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'bewpi_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'bewpi_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'bewpi_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'bewpi_admin_field_template_notification', array( $this, 'template_setting' ) );
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Template Options', 'woocommerce-pdf-invoices' )
		);
		return apply_filters( 'bewpi_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = apply_filters( 'bewpi_template_settings', array(

			array( 'title' => __( 'PDF Templates', 'woocommerce-pdf-invoices' ),  'desc' => __( 'PDF invoice types and documents to attach to WooCommerce emails are listed below. Click on a type to configure it.', 'woocommerce-pdf-invoices' ), 'type' => 'title', 'id' => 'template_settings' ),

			array( 'type' => 'template_notification' ),

			array( 'type' => 'sectionend', 'id' => 'template_settings' ),

			array( 'type' => 'sectionend', 'id' => 'template_recipient_options' ),

			array( 'title' => __( 'General Options', 'woocommerce-pdf-invoices' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

			array( 'type' => 'sectionend', 'id' => 'template_settings' ),

		) );

		return apply_filters( 'bewpi_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		// Define emails that can be customised here
		$templater       = BEWPI()->templater();
		$templates       = $templater->get_templates();

		if ( $current_section ) {
			foreach ( $templates as $template_key => $template ) {
				if ( strtolower( $template_key ) == $current_section ) {
					$template->admin_options();
					break;
				}
			}
		} else {
			$settings = $this->get_settings();
			BEWPI_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		if ( ! $current_section ) {
			BEWPI_Admin_Settings::save_fields( $this->get_settings() );

		} else {
			$templater       = BEWPI()->templater();
			$templates       = $templater->get_templates();

			if ( in_array( $current_section, array_map( 'sanitize_title', array_keys( $templates ) ) ) ) {
				foreach ( $templates as $template_id => $template ) {
					if ( $current_section === sanitize_title( $template_id ) ) {
						do_action( 'bewpi_update_options_' . $this->id . '_' . $template->id );
					}
				}
			} else {
				do_action( 'bewpi_update_options_' . $this->id . '_' . $current_section );
			}
		}
	}

	/**
	 * Output email notification settings.
	 */
	public function template_setting() {
		// Define templates that can be customised here
		$templater       = BEWPI()->templater();
		$templates       = $templater->get_templates();

		?>
		<tr valign="top">
		    <td class="wc_emails_wrapper" colspan="2">
				<table class="wc_emails widefat" cellspacing="0">
					<thead>
						<tr>
							<?php
								$columns = apply_filters( 'bewpi_email_setting_columns', array(
									'status'     => '',
									'name'       => __( 'Email', 'woocommerce' ),
									'email_type' => __( 'Content Type', 'woocommerce' ),
									'recipient'  => __( 'Recipient(s)', 'woocommerce' ),
									'actions'    => ''
								) );
								foreach ( $columns as $key => $column ) {
									echo '<th class="wc-email-settings-table-' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
								}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $templates as $template_key => $template ) {
							echo '<tr>';

							foreach ( $columns as $key => $column ) {

								switch ( $key ) {
									case 'name' :
										echo '<td class="wc-email-settings-table-' . esc_attr( $key ) . '">
											<a href="' . admin_url( 'admin.php?page=bewpi-settings&tab=templates&section=' . strtolower( $template_key ) ) . '">' . $template->get_title() . '</a>
											' . bewpi_help_tip( $template->get_description() ) . '
										</td>';
									break;
									case 'recipient' :
										echo '<td class="wc-email-settings-table-' . esc_attr( $key ) . '">
											' . esc_html( $template->is_customer_email() ? __( 'Customer', 'woocommerce' ) : $template->get_recipient() ) . '
										</td>';
									break;
									case 'status' :
										echo '<td class="wc-email-settings-table-' . esc_attr( $key ) . '">';

										if ( $template->is_manual() ) {
											echo '<span class="status-manual tips" data-tip="' . __( 'Manually sent', 'woocommerce' ) . '">' . __( 'Manual', 'woocommerce' ) . '</span>';
										} elseif ( $template->is_enabled() ) {
											echo '<span class="status-enabled tips" data-tip="' . __( 'Enabled', 'woocommerce' ) . '">' . __( 'Yes', 'woocommerce' ) . '</span>';
										} else {
											echo '<span class="status-disabled tips" data-tip="' . __( 'Disabled', 'woocommerce' ) . '">-</span>';
										}

										echo '</td>';
									break;
									case 'email_type' :
										echo '<td class="wc-email-settings-table-' . esc_attr( $key ) . '">
											' . esc_html( $template->get_content_type() ) . '
										</td>';
									break;
									case 'actions' :
										echo '<td class="wc-email-settings-table-' . esc_attr( $key ) . '">
											<a class="button alignright tips" data-tip="' . __( 'Configure', 'woocommerce' ) . '" href="' . admin_url( 'admin.php?page=wc-settings&tab=email&section=' . strtolower( $template_key ) ) . '">' . __( 'Configure', 'woocommerce' ) . '</a>
										</td>';
									break;
									default :
										do_action( 'bewpi_email_setting_column_' . $key, $template );
									break;
								}
							}

							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}
}

endif;

return new BEWPI_Settings_Templates();