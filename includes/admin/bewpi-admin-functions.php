<?php

/**
 * Get all WooCommerce screen ids.
 *
 * @return array
 */
function bewpi_get_screen_ids() {

	$screen_ids   = array(
		'woocommerce_page_bewpi-settings'
	);

	foreach ( wc_get_order_types() as $type ) {
		$screen_ids[] = $type;
		$screen_ids[] = 'edit-' . $type;
	}

	return apply_filters( 'bewpi_screen_ids', $screen_ids );
}