<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor YITH WooCommerce Ajax Product Filter
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat_YITH_WCAN {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! defined( 'YITH_WCAN' ) ) {
			return;
		}

		$self = new self();
		add_filter( 'yith_wcan_settings_tabs', [ $self, 'remove_premium_tab' ] );
		add_filter( 'yith_wcan_get_terms_list', [ $self, 'order_terms' ], 10, 3 );
		add_filter( 'yith_wcan_body_class', '__return_empty_string' );

		/* Aanpassen diverse woocommerce-hooks voor archive */
		add_filter( 'yith_wcan_untrailingslashit', '__return_false' );
		add_filter( 'yith_wcan_is_search', '__return_false' );
		add_filter( 'yith_wcan_hide_out_of_stock_items', '__return_true' );
		add_filter( 'yith_wcan_skip_layered_nav_query', '__return_false', PHP_INT_MAX );

		/* YITH premium nags verwijderen */
		add_filter( 'yit_plugin_panel_menu_page_show', '__return_false' );
		add_filter( 'yit_show_upgrade_to_premium_version', '__return_false' );
	}

	/**
	 * Verwijdert premium tabs
	 *
	 * @param array $admin_tabs
	 * @return array
	 */
	public function remove_premium_tab( $admin_tabs ) {
		unset( $admin_tabs['premium'] );
		return $admin_tabs;
	}

	/**
	 * Sorteert maand op slug ipv op alfabet
	 *
	 * @param array $terms
	 * @param string $taxonomy
	 * @param YITH_WCAN_Navigation_Widget $instance
	 * @return array
	 */
	public function order_terms( $terms, $taxonomy, $instance ) {
		if ( 'pa_maand' != $taxonomy ) {
			return $terms;
		}
		foreach ( $terms as $index => $term ) {
			$ordered_term_indices[ $index ] = $term->slug;
		}
		asort( $ordered_term_indices, SORT_STRING );
		$order = array_keys( $ordered_term_indices );
	
		uksort( $terms, function( $key1, $key2 ) use ( $order ) {
			return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
		} );

		return $terms;
	}
}