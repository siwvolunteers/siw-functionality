<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Aanpassen diverse woocommerce-hooks voor archive
 * - Prijs verbergen
 * - Add to cart verbergen
 * - AJAX-filtering ook op zoekresultaten-pagina
 * - Trailing slash toevoegen bij AJAX-filtering
*/
add_action( 'plugins_loaded', function() {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	add_filter( 'yith_wcan_untrailingslashit', '__return_false' );
	add_filter( 'yith_wcan_is_search', '__return_false' );
} );


/*
 * Velden toevoegen aan product in productoverzichten
 * - Datums
 * - Projectcode
*/
add_action( 'woocommerce_after_shop_loop_item_title', function() {
	global $product;
	$start_date = $product->get_attribute( 'startdatum' );
	$end_date = $product->get_attribute( 'einddatum' );
	$duration = siw_get_date_range_in_text( $start_date, $end_date, false );
	$project_code = get_post_meta( $product->id, '_sku', true );
	//TODO: inline styling verplaatsen naar css
	echo '<p>' . esc_html( $duration ) . '</p><hr style="margin:5px;">';
	echo '<p style="margin-bottom:5px;"><small>' . esc_html( $project_code ) . '</small></p>';
}, 1 );


/*
 * Voeg extra sorteeropties toe:
 * - Random
 * - Land
 * - Start datum
*/
add_filter( 'woocommerce_get_catalog_ordering_args', function( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	switch ( $orderby_value ) {
		case 'random':
			$sort_args['orderby']	= 'rand';
			$sort_args['order']		= '';
			$sort_args['meta_key']	= '';
			break;
		case 'startdate':
			$sort_args['orderby']	= 'meta_value';
			$sort_args['order']		= 'asc';
			$sort_args['meta_key']	= 'startdatum';
			break;
		case 'country':
			$sort_args['orderby']	= 'meta_value';
			$sort_args['order']		= 'asc';
			$sort_args['meta_key']	= 'land';
			break;
	}
	return $sort_args;
} );

add_filters( array( 'woocommerce_default_catalog_orderby_options', 'woocommerce_catalog_orderby' ), function() {
	$orderby['startdate']	= __( 'Startdatum', 'siw' );
	$orderby['country']		= __( 'Land', 'siw' );
	$orderby['random']		= __( 'Willekeurig', 'siw' );

	return $orderby;
} );


/*
 * Voeg extra sorteeropties toe aan WooCommerce shortcodes:
 * - Random
*/
add_filter( 'woocommerce_shortcode_products_query', function( $args, $atts ) {
	if ( 'random' == $atts['orderby'] ) {
		$args['orderby']  = 'rand';
		$args['order']    = '';
		$args['meta_key'] = '';
	}
	return $args;
	return $atts;
}, 10, 2 );
