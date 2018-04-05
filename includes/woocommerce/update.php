<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined('ABSPATH' ) ) {
	exit;
}


/*
 * Bepaal of project geupdate moet worden tijdens import
 * - In het geval van de FPL-update
 * - Als het project handmatig gemarkeerd is om opnieuw te importeren.
 * - Als de optie 'Forceer volledige update' op true staat.
 * - Als specifieke eigenschappen veranderd zijn.
 */
add_filter( 'wp_all_import_is_post_to_update', function( $continue, $product_id, $xml, $current_import_id ) {
	$product = wc_get_product( $product_id );
	$sku = $product->get_sku();

	$is_full_import = ( $current_import_id == siw_get_setting( 'plato_full_import_id' ) );
	$is_fpl_import = ( $current_import_id == siw_get_setting( 'plato_fpl_import_id' ) );


	if ( $is_fpl_import ) {
		$visibility = $product->is_visible();
		$free_places_current = $product->get_meta( 'freeplaces' );
		$free_places_new = siw_get_workcamp_free_places_left( $xml['free_m'], $xml['free_f'] );

		return ( 'no' != $free_places_current && 'no' == $free_places_new && $visibility ) ? true : false; //FIXME:redundante operator?
	}

	$import_again = $product->get_meta( 'import_again' );
	if ( $is_full_import && $import_again ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Geforceerde update specifiek project', $product_id, $sku ) );
		return true;
	}

	$force_full_update = siw_get_setting( 'plato_force_full_update' );
	if ( $is_full_import && $force_full_update ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Geforceerde update alle projecten', $product_id, $sku ) );
		return true;
	}


	/*
	Project opnieuw importeren als één van de volgende eigenschappen aangepast is.
	- Update in Plate (last_update)
	- Startdatum
	- Eindatum
	- Local fee
	- Projectcode
	- Land toegestaan
	- Land
	- Leeftijd
	- TODO: Nog meer eigenschappen? Bijv. beschrijving, soort werk...
	*/

	/* Als project in Plato is bijgewerkt */
	$last_update_plato = $xml['last_update'];
	$last_update_product = $product->get_date_modified()->date_i18n( 'Y-m-d' );

	if ( '0001-01-01' != $last_update_plato && ( $last_update_product < $last_update_plato ) ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Project bijgewerkt in Plato op %s', $product_id, $sku, $xml['last_update'] ) );
		return true;
	}


	/* Startdatum */
	$start_date_current = $product->get_attribute( 'startdatum' );
	$start_date_new = siw_get_workcamp_formatted_date( $xml['start_date'] );
	if ( $start_date_current != $start_date_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Startdatum veranderd van %s naar %s', $product_id, $sku, $start_date_current, $start_date_new ) );
		return true;
	}

	/* Einddatum */
	$end_date_current = $product->get_attribute( 'einddatum' );
	$end_date_new = siw_get_workcamp_formatted_date( $xml['end_date'] );
	if ( $end_date_current != $end_date_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Einddatum veranderd van %s naar %s', $product_id, $sku, $end_date_current, $end_date_new ) );
		return true;
	}

	/* Local fee */
	$participation_fee_current = $product->get_attribute( 'lokale-bijdrage' );
	$participation_fee_current = html_entity_decode( $participation_fee_current );
	$participation_fee_new = siw_get_workcamp_local_fee( $xml['participation_fee'], isset( $xml['participation_fee_currency'] ) ? $xml['participation_fee_currency'] : '' );
	$participation_fee_new = html_entity_decode( $participation_fee_new );
	if ( $participation_fee_current != $participation_fee_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Lokale bijdrage veranderd van %s naar %s', $product_id, $sku, $participation_fee_current, $participation_fee_new ) );
		return true;
	}

	/* Local fee */
	$projectcode_current = $product->get_attribute( 'projectcode' );
	$projectcode_new = $xml['code'];
	if ( $projectcode_current != $projectcode_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Projectcode veranderd van %s naar %s', $product_id, $sku, $projectcode_current, $projectcode_new ) );
		return true;
	}

	/* Land */
	$country_current = $product->get_attribute( 'land' );
	$country_new = siw_get_workcamp_country_name( $xml['country'] );
	if ( $country_current != $country_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Land veranderd van %s naar %s', $product_id, $sku, $country_current, $country_new ) );
		return true;
	}

	/* Land toegestaan*/
	$country_allowed_current = $product->get_meta( 'allowed' );
	$country_allowed_new = siw_get_workcamp_country_allowed( $xml['country'] );
	if ( $country_allowed_current != $country_allowed_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Status land veranderd van %s naar %s', $product_id, $sku, $country_allowed_current, $country_allowed_new ) );
		return true;
	}

	/* Leeftijd */
	$age_range_current = $product->get_attribute( 'leeftijd' );
	$age_range_new = siw_get_workcamp_age_range( $xml['min_age'], $xml['max_age'] );
	if ( $age_range_current != $age_range_new ) {
		siw_debug_log( sprintf( 'Update project %s (%s): Leeftijd veranderd van %s naar %s', $product_id, $sku, $age_range_current, $age_range_new ) );
		return true;
	}


	return false;
}, 10, 4 );


/*
 * Functie om groepsprojecten te verbergen die aan 1 of meer van onderstaande voorwaarden voldoen:
 * - Het project begint binnen x dagen (configuratie)
 * - Het project is in een niet-toegestaan land
 * - Het project is expliciet verborgen
 * - Er zijn geen vrije plaatsen meer
 */
add_action( 'siw_hide_workcamps', function() {

	siw_debug_log( 'Verbergen projecten gestart.' );

	$days = siw_get_setting( 'plato_hide_project_days_before_start' ); //TODO:constante van maken
	$limit = date( 'Y-m-d', time() + ( $days * DAY_IN_SECONDS ) );

	$tax_query = array(
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'slug',
			'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
			'operator' => 'NOT IN',
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

	$products = get_posts( $args ); //TODO: wc_get_products

	// Afbreken als er geen te verbergen projecten zijn
	if ( empty( $products ) ) {
		siw_debug_log( 'Verbergen projecten voltooid: geen projecten te verbergen' );
		return;
	}
	siw_start_background_process( 'hide_workcamps', $products );
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
	/* Afbreken als product niet meer bestaat */
	if ( false == $product ) {
		return false;
	}
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



/*
 * Bijwerken tarieven van alle zichtbare projecten
 */
siw_add_cron_job( 'siw_update_workcamp_tariffs' );

add_action( 'siw_update_workcamp_tariffs', function() {
	siw_debug_log( 'Bijwerken tarieven gestart.' );
	$args = array(
		'visibility'	=> 'visible',
		'return'		=> 'ids',
		'limit'			=> -1,
	);
	$products = wc_get_products( $args );

	siw_start_background_process( 'update_workcamp_tariffs', $products );
});


/**
 * Werkt tarieven van groepsproject bij
 * @param  int $product_id
 * @return bool
 */
function siw_update_workcamp_tariff( $product_id ) {
	$tariff_array = siw_get_workcamp_tariffs();

	$product = wc_get_product( $product_id );

	/* Afbreken als product niet meer bestaat */
	if ( false == $product ) {
		return false;
	}

	$variations = $product->get_children();

	foreach ( $variations as $variation_id ) {
		$variation = wc_get_product( $variation_id );
		$tariff = $variation->get_attributes()['pa_tarief'];
		$price = isset( $tariff_array[ $tariff ] ) ? $tariff_array[ $tariff ] : $tariff_array['regulier'];
		$variation->set_price( $price );
		$variation->set_regular_price( $price );
		$variation->save();
	}

	return true;
}
