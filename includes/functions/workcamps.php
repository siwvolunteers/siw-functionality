<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Tel zichtbare projecten per term
 *
 * @param string $taxonomy
 * @param string $term_slug
 * @param bool $force_recount
 * @return void
 */
function siw_count_projects_by_term( $taxonomy, $term_slug, $force_recount = false ) {

	$count = get_transient( "siw_project_count_{$taxonomy}_{$term_slug}" );
	if ( false === $count || true === $force_recount ) {
		$tax_query = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			),
		);
	
		$products = wc_get_products(
			array(
				'status'		=> 'publish',
				'limit'			=> -1,
				'return'		=> 'ids',
				'visibility'	=> 'visible',
				'tax_query' 	=> $tax_query,
			)
		);
		$count = count( $products );

		set_transient( "siw_project_count_{$taxonomy}_{$term_slug}", $count, DAY_IN_SECONDS );
	}

	return $count;
}


/**
 * Bepaal of kortingsactie actief is
 *
 * @return bool
 */
function siw_is_sale_active() {

	$sale_active = false;

	if ( siw_get_setting( 'workcamp_sale_active' ) &&
		date( 'Y-m-d' ) >= siw_get_setting( 'workcamp_sale_start_date' ) &&
		date( 'Y-m-d' ) <= siw_get_setting( 'workcamp_sale_end_date' )
		) {
			$sale_active = true;
	}

	return $sale_active;
}
