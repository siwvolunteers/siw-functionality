<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * strpos with array of needles
 *
 * @param string $haystack
 * @param array $needles
 * @return int
 */
function strpos_arr( $haystack, $needles ) {
    if( ! is_array( $needles ) ) {
		$needles = array( $needles );
	}
    foreach( $needles as $needle ) {
        if ( ( $pos = strpos( $haystack, $needle ) ) !== false ) {
			return $pos;
		}
    }
    return false;
}


/**
 * Hulpfunctie om cronjob toe te voegen
 * @param string $job [description]
 */
function siw_add_cron_job( $job ) {
	add_filter( 'siw_cron_jobs', function( $jobs ) use( $job ) {
		$jobs[] = $job;
		return $jobs;
	});
}


/**
 * Zoek id van vertaalde pagina op basis van id
 * @param  int $page_id
 * @return int
 */
function siw_get_translated_page_id( $page_id ) {
	$translated_page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true );
	return $translated_page_id;
}


/**
 * Zoek url van vertaalde pagina op basis van id
 * @param  int $page_id
 * @return string
 */
function siw_get_translated_page_link( $page_id ) {
	$translated_page_id = siw_get_translated_page_id( $page_id );
	$translated_page_link = get_page_link( $translated_page_id );
	return $translated_page_link;
}

/**
 * Geeft een array met gewhiteliste IP-adressen terug
 *
 * @return array
 */
function siw_get_ip_whitelist() {
	for ( $x = 1 ; $x <= SIW_IP_WHITELIST_SIZE; $x++ ) {
		$ip_whitelist[] = siw_get_setting( "whitelist_ip_{$x}" );
	}
	return $ip_whitelist;
}


/**
 * Geeft array met tarieven groepsprojecten terug
 *
 * @return array
 */
function siw_get_workcamp_tariffs() {
	$workcamp_tariffs = array(
		'regulier'	=> number_format( SIW_WORKCAMP_FEE_REGULAR, 2 ),
		'student'	=> number_format( SIW_WORKCAMP_FEE_STUDENT, 2 )
	);
	return $workcamp_tariffs;
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
 * Geeft array met pagina's terug
 *
 * @return array id => naam
 */
function siw_get_pages() {
	$default_lang = apply_filters( 'wpml_default_language', NULL );
	$current_lang = apply_filters( 'wpml_current_language', NULL );
	do_action( 'wpml_switch_language', $default_lang );
	$results = get_pages();
	do_action( 'wpml_switch_language', $current_lang );

	$pages = array();
	foreach ( $results as $result ) {
		$ancestors = get_ancestors( $result->ID, 'page' );
		$prefix = str_repeat ( '-', sizeof( $ancestors ) );
		$pages[ $result->ID ] = $prefix . esc_html( $result->post_title );
	}
	return $pages;
}


/**
 * Geeft array met WPAI imports terug
 *
 * @return array
 */
function siw_get_wpai_imports() {
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_imports ) ) {
		$wpdb->pmxi_imports = $wpdb->prefix . 'pmxi_imports';
	}
	$query = "SELECT $wpdb->pmxi_imports.id, $wpdb->pmxi_imports.friendly_name, $wpdb->pmxi_imports.name FROM $wpdb->pmxi_imports ORDER BY $wpdb->pmxi_imports.friendly_name ASC";
	$results = $wpdb->get_results( $query, ARRAY_A);
	foreach ( $results as $result ) {
		$imports[$result['id']] = esc_html( $result['friendly_name'] . ' (' . $result['name'] . ')' );
	}
	return $imports;
}


/**
 * Geeft array met Mapplic-kaarten terug
 *
 * @return array
 */
function siw_get_mapplic_maps() {
	$query_args = array(
		'post_type'				=> 'mapplic_map',
		'posts_per_page'		=> -1,
		'post_status'			=> 'publish',
		'ignore_sticky_posts'	=> true,
		'orderby'				=> 'title',
		'order'					=> 'ASC',
		'fields' 				=> 'ids',
	);
	$post_ids = get_posts( $query_args );

	if ( empty( $post_ids ) ) {
		return;
	}
	foreach ( $post_ids as $post_id ) {
		$mapplic_maps[ $post_id ] = get_the_title( $post_id );
	}
	return $mapplic_maps;
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
* Haal array van bestuursleden (met functie) op
* @return array
 */
function siw_get_board_members() {
	for ( $x = 1 ; $x <= SIW_MAX_BOARD_MEMBERS; $x++ ) {
		$board_members[] = siw_get_setting( "board_member_{$x}" );
	}
	return $board_members;
}


/**
 * Haal array van jaarverslagen op
 * @return array
 */
function siw_get_annual_reports() {
	$last_year = (int) date( 'Y' ) - 1;
	$first_year = $last_year - SIW_MAX_ANNUAL_REPORTS + 1;

	for ( $x = $last_year ; $x >= $first_year; $x-- ) {
		$annual_reports[ $x ] = siw_get_setting( "annual_report_{$x}" );
	}
	return $annual_reports;
}


