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
 * Zoek bestemmingen met zichtbare projecten voor Snel Zoeken
 *
 * @return array
 */
function siw_get_quick_search_destinations() {

	$categories = get_terms( array(
		'taxonomy'		=> 'product_cat',
		'hide_empty'	=> false,
	) );

	$destinations = array(
		'' => __( 'Waar wil je heen?', 'siw' ),
	);
	foreach ( $categories as $category ) {
		if ( 'uncategorized' != $category->slug && siw_count_projects_by_term( 'product_cat', $category->slug ) > 0 ) {
			$destinations[ $category->slug ] = $category->name;
		}
	}

	return $destinations;
}


/**
 * Zoek maanden met zichtbare projecten voor Snel Zoeken
 *
 * @return array
 */
function siw_get_quick_search_months() {
	$terms = get_terms( array(
		'taxonomy'		=> 'pa_maand',
		'hide_empty'	=> false,
	) );

	$months = array(
		'' => __( 'Wanneer wil je weg?', 'siw' ),
	);
	foreach ( $terms as $term ) {
		if ( siw_count_projects_by_term( 'pa_maand', $term->slug ) > 0 ) {
			$months[ $term->slug ] = $term->name; 
		}
	}

	return $months;
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
