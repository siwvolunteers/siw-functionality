<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined('ABSPATH') ) {
	exit;
}


/*
 * Bepaal of project geupdate moet worden tijdens import
 * - In het geval van de FPL-update
 * - Als het project handmatig gemarkeerd is om opnieuw te importeren.
 * - Als de optie 'Forceer volledige update' op true staat.
 */
add_filter('wp_all_import_is_post_to_update', function( $product_id, $xml, $current_import_id ) {

	$fpl_import_id = siw_get_setting('plato_fpl_import_id');
	if ( $current_import_id == $fpl_import_id ) {
		return true;
	}

	$import_again = get_post_meta( $product_id, 'import_again', true);
	if ( $import_again ) {
		return true;
	}

	$force_full_update = siw_get_setting('plato_force_full_update');
	if ( $force_full_update ) {
		return true;
	}

	/* TODO:
	Project opnieuw importeren als één van de volgende eigenschappen aangepast is.
	- Startdatum
	- Eindatum
	- Local fee
	- Projectcode
	- Beschrijving
	*/

	return false;
}, 10, 3);


/*
 * Functie om groepsprojecten te verbergen die aan 1 of meer van onderstaande voorwaarden voldoen:
 * - Het project begint binnen x dagen (configuratie)
 * - Het project is in een niet-toegestaan land
 * - Het project is expliciet verborgen
 * - Er zijn geen vrije plaatsen meer
 */
add_action('siw_hide_workcamps', function() {
	$days = siw_get_setting('plato_hide_project_days_before_start');
	$limit = date('Y-m-d', time() + ( $days * DAY_IN_SECONDS) );

	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'_visibility',
			'value'		=>	'visible',
			'compare'	=>	'='
		),
		array(
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
		),
	);

	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'meta_query'		=> $meta_query_args,
		'fields' 			=> 'ids',
		'post_status'		=> 'any',
	);

	$products = get_posts( $args );
	foreach ( $products as $product_id ) {
		//project 'publiceren' als project eigenlijk ter review stond
		if ('publish' != get_post_status( $product_id ) ) {
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
	update_post_meta( $product_id, '_visibility', 'hidden');
	update_post_meta( $product_id, '_stock_status', 'outofstock');
	update_post_meta( $product_id, '_featured', 'no');
	update_post_meta( $product_id, '_yoast_wpseo_meta-robots-noindex','1');

	$varationsargs = array(
		'post_type' 	=> 'product_variation',
		'post_parent'	=> $product_id,
		'fields' 		=> 'ids'
	);
	$variations = get_posts( $varationsargs );
	foreach ( $variations as $variation_id ) {
		update_post_meta( $variation_id, '_stock_status', 'outofstock');
	}
}
