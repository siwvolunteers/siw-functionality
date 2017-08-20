<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined('ABSPATH' ) ) {
	exit;
}


/*
 * Bepaal of project geupdate moet worden tijdens import
 * - In het geval van de FPL-update
 * - Als het project handmatig gemarkeerd is om opnieuw te importeren.
 * - Als de optie 'Forceer volledige update' op true staat.
 */
add_filter( 'wp_all_import_is_post_to_update', function( $product_id, $xml, $current_import_id ) {
	$product = wc_get_product( $product_id );
	$is_full_import = ( $current_import_id == siw_get_setting( 'plato_full_import_id' ) );
	$is_fpl_import = ( $current_import_id == siw_get_setting( 'plato_fpl_import_id' ) );


	if ( $is_fpl_import ) {
		$visibility = $product->is_visible();
		$free_places_current = $product->get_meta( 'freeplaces' );
		$free_places_new = siw_get_workcamp_free_places_left( $xml['free_m'], $xml['free_f'] );

		return ( 'no' != $free_places_current && 'no' == $free_places_new && $visibility ) ? true : false; //redundante operator?
	}

	$import_again = $product->get_meta( 'import_again' );
	if ( ! $is_fpl_import && $import_again ) {
		return true;
	}

	$force_full_update = siw_get_setting( 'plato_force_full_update' );
	if ( $is_full_import && $force_full_update ) {
		return true;
	}


	/*
	Project opnieuw importeren als één van de volgende eigenschappen aangepast is.
	- Startdatum
	- Eindatum
	- Local fee
	- Projectcode
	- Land toegestaan
	- TODO: Nog meer eigenschappen? Bijv. beschrijving, soort werk...
	*/
	$current_attributes = get_post_meta( $product_id, '_product_attributes', true ); //TODO:crud $product->get_attributes();

	/* Startdatum */
	$start_date_current = $product->get_attribute( 'startdatum' );
	$start_date_new = siw_get_workcamp_formatted_date( $xml['start_date'] );
	if ( $start_date_current != $start_date_new ) {
		return true;
	}

	/* Einddatum */
	$end_date_current = $product->get_attribute( 'einddatum' );
	$end_date_new = siw_get_workcamp_formatted_date( $xml['end_date'] );
	if ( $end_date_current != $end_date_new ) {
		return true;
	}

	/* Local fee */
	$participation_fee_current = $product->get_attribute( 'lokale-bijdrage' );
	$participation_fee_new = siw_get_workcamp_local_fee( $xml['participation_fee'], $xml['participation_fee_currency'] );
	if ( $participation_fee_current != $participation_fee_new ) {
		return true;
	}

	/* Local fee */
	$projectcode_current = $product->get_attribute( 'projectcode' );
	$projectcode_new = $xml['code'];
	if ( $projectcode_current != $projectcode_new ) {
		return true;
	}

	/* Land toegestaan*/
	$country_allowed_current = $product->get_meta( 'allowed' );
	$country_allowed_new = siw_get_workcamp_country_allowed( $xml['country'] );
	if ( $country_allowed_current != $country_allowed_new ) {
		return true;
	}


	return false;
}, 10, 3 );


/*
 * Functie om groepsprojecten te verbergen die aan 1 of meer van onderstaande voorwaarden voldoen:
 * - Het project begint binnen x dagen (configuratie)
 * - Het project is in een niet-toegestaan land
 * - Het project is expliciet verborgen
 * - Er zijn geen vrije plaatsen meer
 */
add_action( 'siw_hide_workcamps', function() {
	$days = siw_get_setting( 'plato_hide_project_days_before_start' );
	$limit = date( 'Y-m-d', time() + ( $days * DAY_IN_SECONDS ) );

	$tax_query = array(
		array(
			'taxonomy' => 'product_visibility',
			'operator' => 'NOT EXISTS',
		),
	);
	$meta_query = array(
		'relation'	=>	'OR',
		array(
			'key'		=> 'freeplaces',
			'value'		=> 'no',
			'compare'	=> '='
		),
		array(
			'key'		=> 'manual_visibility',
			'value'		=> 'hide',
			'compare'	=> '='
		),
		array(
			'key'		=> 'startdatum',
			'value'		=> $limit,
			'compare'	=> '<='
		),
		array(
			'key'		=> 'allowed',
			'value'		=> 'no',
			'compare'	=> '='
		),
	);

	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'meta_query'		=> $meta_query,
		'tax_query'			=> $tax_query,
		'fields' 			=> 'ids',
		'post_status'		=> 'any',
	);

	$products = get_posts( $args );
	foreach ( $products as $product_id ) {
		//project 'publiceren' als project eigenlijk ter review stond
		if ( 'publish' != get_post_status( $product_id ) ) {
			wp_publish_post( $product_id );
		}
		siw_hide_workcamp( $product_id );
	}
});


/**
 * Verberg groepsproject
 *
 * @param int $product_id
 *
 * @return void
 */
function siw_hide_workcamp( $product_id ) {
	$product = wc_get_product( $product_id );
	$product->set_catalog_visibility( 'hidden' );
	$product->set_stock_status( 'outofstock' );
	$product->set_featured( 'no' );
	siw_seo_set_noindex( $product_id, true );
	$product->save();

	$variation_ids = $product->get_children();
	foreach ( $variation_ids as $variation_id ) {
		$variation = wc_get_product( $variation_id );
		$variation->set_stock_status( 'outofstock' );
		$variation->save();
	}
}
