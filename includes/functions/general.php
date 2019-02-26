<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met gewhiteliste IP-adressen terug
 *
 * @return array
 * 
 * @deprecated
 */
function siw_get_ip_whitelist() {
	for ( $x = 1 ; $x <= SIW_Properties::IP_WHITELIST_SIZE; $x++ ) {
		$ip_whitelist[] = siw_get_setting( "whitelist_ip_{$x}" );
	}
	return $ip_whitelist;
}


/**
 * Geeft array met Mailpoet-lijsten terug
 *
 * @return array id => naam
 */
function siw_get_mailpoet_lists() {
	if ( ! class_exists( 'WYSIJA' ) ) {
		return;
	}
	$model_list = WYSIJA::get( 'list','model' );
	$lists = $model_list->get( array( 'name','list_id' ), array( 'is_enabled' => 1 ) );
	foreach ( $lists as $list ) {
		$mailpoet_lists[ $list['list_id'] ] = $list['name'];
	}
	return $mailpoet_lists;
}



/**
 * Geeft array met gegevens van een quote terug
 *
 * @param  string $category
 * @return array
 */
function siw_get_testimonial_quote( $category = '' ) {

	$query_args = array(
		'post_type'				=> 'testimonial',
		'posts_per_page'		=> 1,
		'post_status'			=> 'publish',
		'ignore_sticky_posts'	=> true,
		'orderby'				=> 'rand',
		'fields' 				=> 'ids',
		'testimonial-group'		=> $category,
	);
	$post_ids = get_posts( $query_args );

	if ( empty( $post_ids ) ) {
		return;
	}

	$post_id = $post_ids[0];
	$testimonial_quote['quote'] = get_post_field('post_content', $post_id );
	$testimonial_quote['name'] = get_the_title( $post_id );
	$testimonial_quote['project'] = get_post_meta( $post_id, '_kad_testimonial_location', true );
	return $testimonial_quote;
}


/**
 * Geeft lijst van categorieën voor quotes terug
 *
 * @return array
 */
function siw_get_testimonial_quote_categories() {
	$testimonial_groups = get_terms( 'testimonial-group' );
	$testimonial_quote_categories[''] =  __( 'Alle', 'siw' );
	foreach ( $testimonial_groups as $testimonial_group ) {
		$testimonial_quote_categories[ $testimonial_group->slug ] = $testimonial_group->name;
	}
	return $testimonial_quote_categories;
}


/**
 * Haal array van jaarverslagen op
 * @return array
 */
function siw_get_annual_reports() {
	$last_year = (int) date( 'Y' ) - 1;
	$first_year = $last_year - SIW_Properties::MAX_ANNUAL_REPORTS + 1;

	for ( $x = $last_year ; $x >= $first_year; $x-- ) {
		$annual_reports[ $x ] = siw_get_setting( "annual_report_{$x}" );
	}
	return $annual_reports;
}


/**
 * Geeft array met Nederlandse Projecten terug
 * @return array
 */
function siw_get_dutch_projects() {
	$dutch_projects = array();
	$properties = array(
		'name',
		'city',
		'province',
		'latitude',
		'longitude',
		'start_date',
		'end_date',
		'work',
		'participants',
	);
	$types = siw_get_work_types( 'dutch_projects' );
	foreach ( $types as $type ) {
		$work_types[ $type->get_slug() ] = $type->get_name();
	}
	$provinces = siw_get_dutch_provinces();

	for ( $x = 1 ; $x <=  SIW_Properties::MAX_DUTCH_PROJECTS; $x++ ) {
		$present = siw_get_setting( "np_project_{$x}_present" );

		if ( ! $present ) {
			continue;
		}

		foreach ( $properties as $property ) {
			$dutch_projects[ $x ][ $property ] = siw_get_setting( "np_project_{$x}_$property" );
		}
		$dutch_projects[ $x ][ 'work_name' ] = $work_types[ $dutch_projects[ $x ][ 'work' ] ];
		$dutch_projects[ $x ][ 'province_name' ] = $provinces[	$dutch_projects[ $x ][ 'province' ] ];
	}

	return $dutch_projects;
}
