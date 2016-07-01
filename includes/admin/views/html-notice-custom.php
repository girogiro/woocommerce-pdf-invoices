<?php
/**
 * Admin View: Custom Notices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="updated woocommerce-message">
    <a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'bewpi-hide-notice', $notice ), 'bewpi_hide_notices_nonce', '_bewpi_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'woocommerce' ); ?></a>
	<?php echo wp_kses_post( wpautop( $notice_html ) ); ?>
</div>
