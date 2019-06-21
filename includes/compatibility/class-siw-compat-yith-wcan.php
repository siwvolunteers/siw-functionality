<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor YITH WooCommerce Ajax Product Filter
 * 
 * @package     SIW\Compatibility
 * @copyright   2018-2019 SIW Internationale Vrijwilligersprojecten
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
		add_action( 'wp_dashboard_setup', [ $self, 'remove_dashboard_widgets' ] );

		/* Aanpassen diverse woocommerce-hooks voor archive */
		add_filter( 'yith_wcan_untrailingslashit', '__return_false' );
		add_filter( 'yith_wcan_is_search', '__return_false' );
		add_filter( 'yith_wcan_hide_out_of_stock_items', '__return_true' );
		add_filter( 'yith_wcan_skip_layered_nav_query', '__return_false', PHP_INT_MAX );

		/* YITH premium nags verwijderen */
		add_filter( 'yit_plugin_panel_menu_page_show', '__return_false' );
		add_filter( 'yit_show_upgrade_to_premium_version', '__return_false' );

		/* Inline script toevoegen */
		add_action( 'wp_enqueue_scripts', [ $self, 'add_scroll_script' ], PHP_INT_MAX );
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
	 * Verwijdert dashboard widgets
	 */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'yith_dashboard_products_news', 'dashboard', 'normal' );
		remove_meta_box( 'yith_dashboard_blog_news', 'dashboard', 'normal' );
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
		if ( 'pa_maand' != $taxonomy || empty( $terms )) {
			return $terms;
		}
		foreach ( $terms as $index => $term ) {
			$ordered_term_indices[ $index ] = get_term_meta( $term->term_id, "order_{$taxonomy}", true );
		}
		asort( $ordered_term_indices, SORT_STRING );
		$order = array_keys( $ordered_term_indices );
	
		uksort( $terms, function( $key1, $key2 ) use ( $order ) {
			return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
		} );

		return $terms;
	}

	/**
	 * Voegt scroll script toe
	 */
	public function add_scroll_script() {
		$inline_script = "
		$( document ).on( 'yith-wcan-ajax-filtered', function() {
			$( document ).scrollTo( $( '.kad-shop-top' ), 800 );
		});";
		wp_add_inline_script( 'yith-wcan-script', "(function( $ ) {" . $inline_script . "})( jQuery );" );//TODO:format-functie voor anonymous jQuery
	}
}
