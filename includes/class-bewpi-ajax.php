<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BEWPI_AJAX
 */
class BEWPI_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_bewpi_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	 * Get WC Ajax Endpoint.
	 *
	 * @param  string $request Optional
	 *
	 * @return string
	 */
	public static function get_endpoint( $request = '' ) {
		return esc_url_raw( add_query_arg( 'bewpi-ajax', $request, remove_query_arg( array(
			'remove_item',
			'add-to-cart',
			'added-to-cart'
		) ) ) );
	}

	/**
	 * Set WC AJAX constant and headers.
	 */
	public static function define_ajax() {
		if ( ! empty( $_GET['bewpi-ajax'] ) ) {
			if ( ! defined( 'DOING_AJAX' ) ) {
				define( 'DOING_AJAX', true );
			}
			if ( ! defined( 'WC_DOING_AJAX' ) ) {
				define( 'WC_DOING_AJAX', true );
			}
			// Turn off display_errors during AJAX events to prevent malformed JSON
			if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
				@ini_set( 'display_errors', 0 );
			}
			$GLOBALS['wpdb']->hide_errors();
		}
	}

	/**
	 * Send headers for WC Ajax Requests
	 * @since 2.5.0
	 */
	private static function bewpi_ajax_headers() {
		send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();
		status_header( 200 );
	}

	/**
	 * Check for WC Ajax request and fire action.
	 */
	public static function do_bewpi_ajax() {
		global $wp_query;

		if ( ! empty( $_GET['bewpi-ajax'] ) ) {
			$wp_query->set( 'bewpi-ajax', sanitize_text_field( $_GET['bewpi-ajax'] ) );
		}

		if ( $action = $wp_query->get( 'bewpi-ajax' ) ) {
			self::bewpi_ajax_headers();
			do_action( 'bewpi_ajax_' . sanitize_text_field( $action ) );
			die();
		}
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		// woocommerce_EVENT => nopriv
		$ajax_events = array(
			'get_refreshed_fragments'                          => true,
			'apply_coupon'                                     => true,
			'remove_coupon'                                    => true,
			'update_shipping_method'                           => true,
			'get_cart_totals'                                  => true,
			'update_order_review'                              => true,
			'add_to_cart'                                      => true,
			'checkout'                                         => true,
			'get_variation'                                    => true,
			'feature_product'                                  => false,
			'mark_order_status'                                => false,
			'add_attribute'                                    => false,
			'add_new_attribute'                                => false,
			'remove_variation'                                 => false,
			'remove_variations'                                => false,
			'save_attributes'                                  => false,
			'add_variation'                                    => false,
			'link_all_variations'                              => false,
			'revoke_access_to_download'                        => false,
			'grant_access_to_download'                         => false,
			'get_customer_details'                             => false,
			'add_order_item'                                   => false,
			'add_order_fee'                                    => false,
			'add_order_shipping'                               => false,
			'add_order_tax'                                    => false,
			'remove_order_item'                                => false,
			'remove_order_tax'                                 => false,
			'reduce_order_item_stock'                          => false,
			'increase_order_item_stock'                        => false,
			'add_order_item_meta'                              => false,
			'remove_order_item_meta'                           => false,
			'calc_line_taxes'                                  => false,
			'save_order_items'                                 => false,
			'load_order_items'                                 => false,
			'add_order_note'                                   => false,
			'delete_order_note'                                => false,
			'json_search_products'                             => false,
			'json_search_products_and_variations'              => false,
			'json_search_grouped_products'                     => false,
			'json_search_downloadable_products_and_variations' => false,
			'json_search_customers'                            => false,
			'term_ordering'                                    => false,
			'product_ordering'                                 => false,
			'refund_line_items'                                => false,
			'delete_refund'                                    => false,
			'rated'                                            => false,
			'update_api_key'                                   => false,
			'get_customer_location'                            => true,
			'load_variations'                                  => false,
			'save_variations'                                  => false,
			'bulk_edit_variations'                             => false,
			'tax_rates_save_changes'                           => false,
			'shipping_zones_save_changes'                      => false,
			'shipping_zone_add_method'                         => false,
			'shipping_zone_methods_save_changes'               => false,
			'shipping_zone_methods_save_settings'              => false,
			'shipping_classes_save_changes'                    => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_bewpi_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_bewpi_' . $ajax_event, array( __CLASS__, $ajax_event ) );

				// WC AJAX can be used for frontend ajax requests
				add_action( 'bewpi_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}
}