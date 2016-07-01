<?php
/**
 * Admin View: Notice - Install
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="updated woocommerce-message wc-connect">
	<p>
		<?php _e( '<strong>Welcome to WooCommerce PDF Invoices</strong> &#8211; You&lsquo;re almost ready to send invoices :)', 'woocommerce-pdf-invoices' ); ?>
	</p>
	<p class="submit">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=bewpi-settings' ) ); ?>" class="button-primary"><?php _e( 'Setup', 'woocommerce-pdf-invoices' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'bewpi-hide-notice', 'install' ), 'bewpi_hide_notices_nonce', '_bewpi_notice_nonce' ) ); ?>"><?php _e( 'Skip Setup', 'woocommerce-pdf-invoices' ); ?></a>
	</p>
</div>
