<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display a help tip.
 *
 * @since  3.0.0
 *
 * @param  string $tip        Help tip text
 * @param  bool   $allow_html Allow sanitized HTML if true or escape
 * @return string
 */
function bewpi_help_tip( $tip, $allow_html = false ) {
	if ( $allow_html ) {
		$tip = wc_sanitize_tooltip( $tip );
	} else {
		$tip = esc_attr( $tip );
	}

	return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
}