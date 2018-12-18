<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hulpfunctie om cronjob toe te voegen
 * @param string $action
 */
function siw_add_cron_job( $action ) {
	add_filter( 'siw_cron_jobs', function( $actions ) use( $action ) {
		$actions[] = $action;
		return $actions;
	});
}


/**
 * Hulpfunctie om admin bar actie toe te voegen
 * @param string $action
 * @param array $properties
 */
function siw_add_admin_bar_action( $action, $properties ) {
	add_filter( 'siw_admin_bar_actions', function( $actions ) use( $action, $properties ) {
		$actions[ $action ] = $properties;
		return $actions;
	});
}


/**
 * Hulpfunctie om admin bar node toe te voegen
 *
 * @param string $node
 * @param array $properties
 * @return void
 */
function siw_add_admin_bar_node( $node, $properties ) {
	add_filter( 'siw_admin_bar_nodes', function( $nodes ) use( $node, $properties ) {
		$nodes[ $node ] = $properties;
		return $nodes;
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
 * Geef reguliere expressie terug
 *
 * @param string $type
 * @return string
 */
function siw_get_regex( $type ) {
	$expressions = array(
		'date' => '/^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$/',
		'postal_code' => '/^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/',
	);
	if ( ! isset( $expressions[ $type ] ) ) {
		return false;
	}
	$regex = $expressions[ $type ];
	return $regex;
}


/**
 * Hulpfunctie om report-uri te genereren
 *
 * @param string $type
 * @param boolean $enforce
 * @return string
 */
function siw_generate_report_uri( $type, $enforce = true ) {
	$types = array( 
		'csp',
		'ct',
		'xss',
		'staple',
	);
	if( ! in_array( $type, $types ) ) {
		return false;
	}
	$action = ( $enforce ) ? 'enforce' : 'reportOnly';
	$report_uri = SIW_REPORT_URI . 'r/d/' . $type .'/' . $action;
	return $report_uri;
}


/**
 * Geeft array met tarieven Groepsprojecten terug
 *
 * @return array
 */
function siw_get_workcamp_tariffs() {
	$workcamp_tariffs = array(
		'regulier'				=> number_format( SIW_WORKCAMP_FEE_REGULAR, 2 ),
		'student'				=> number_format( SIW_WORKCAMP_FEE_STUDENT, 2 ),
		'regulier_aanbieding'	=> number_format( SIW_WORKCAMP_FEE_REGULAR_SALE, 2 ),
		'student_aanbieding'	=> number_format( SIW_WORKCAMP_FEE_STUDENT_SALE, 2 ),
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

	for ( $x = 1 ; $x <= SIW_MAX_DUTCH_PROJECTS; $x++ ) {
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
