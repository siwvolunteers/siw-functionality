<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Zet noindex voor evenementen waarvan de deadline verstreken is */
siw_add_cron_job( 'siw_set_noindex_for_expired_jobs' );

add_action( 'siw_set_noindex_for_expired_jobs', function() {
	$args = array(
		'post_type'			=> 'vacatures',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$job_ids = get_posts( $args );
	foreach ( $job_ids as $job_id ) {
		$noindex = 0;
		$deadline_ts = get_post_meta( $job_id, 'siw_vacature_deadline', true );
		if ( $deadline_ts < time() ) {//TODO:vergelijken datum i.p.v. ts
			$noindex = 1;
			//TODO:uitgelicht op off zetten
		}
		siw_seo_set_noindex( $job_id, $noindex );
	}
} );


/**
 * Haal gegevens van vacature op
 *
 * @param int $post_id
 * @return array
 */
function siw_get_job_data( $post_id ) {
	$deadline_ts							= get_post_meta( $post_id, 'siw_vacature_deadline', true );
	$job_data['permalink']					= get_permalink( $post_id );
	$job_data['title']						= get_the_title( $post_id );
	$job_data['deadline_datum']				= date( 'Y-m-d', $deadline_ts );
	$job_data['deadline']					= siw_get_date_in_text( date( 'Y-m-d', $deadline_ts ), false);
	$job_data['inleiding']					= get_post_meta( $post_id, 'siw_vacature_inleiding', true );
	$job_data['highlight_quote']			= get_post_meta( $post_id, 'siw_vacature_highlight_quote', true );
	$job_data['uur_per_week']				= get_post_meta( $post_id, 'siw_vacature_uur_per_week', true );
	$job_data['wie_ben_jij']				= get_post_meta( $post_id, 'siw_vacature_wie_ben_jij', true );
	$job_data['wie_ben_jij_lijst']			= get_post_meta( $post_id, 'siw_vacature_wie_ben_jij_lijst', true );
	$job_data['wat_ga_je_doen']				= get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen', true );
	$job_data['wat_bieden_wij_jou']			= get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou', true );
	$job_data['contactpersoon_naam']		= get_post_meta( $post_id, 'siw_vacature_contactpersoon_naam', true );
	$job_data['contactpersoon_functie']		= get_post_meta( $post_id, 'siw_vacature_contactpersoon_functie', true );
	if ( $job_data['contactpersoon_functie'] ) {
		$job_data['contactpersoon_naam']	= $job_data['contactpersoon_naam'] . ' (' . $job_data['contactpersoon_functie'] . ')';
	}
	$job_data['contactpersoon_email']		= antispambot( get_post_meta( $post_id, 'siw_vacature_contactpersoon_email', true ) );
	$job_data['contactpersoon_telefoon']	= get_post_meta( $post_id, 'siw_vacature_contactpersoon_telefoon', true );// Wordt nog niet gebruikt
	$job_data['solliciteren_naam']			= get_post_meta( $post_id, 'siw_vacature_solliciteren_naam', true );
	$job_data['solliciteren_functie']		= get_post_meta( $post_id, 'siw_vacature_solliciteren_functie', true );
	if (  $job_data['solliciteren_functie'] ) {
		$job_data['solliciteren_naam'] 		=  $job_data['solliciteren_naam'] . ' (' .  $job_data['solliciteren_functie'] . ')';
	}
	$job_data['solliciteren_email']			= antispambot( get_post_meta( $post_id, 'siw_vacature_solliciteren_email', true ) );
	$job_data['toelichting_solliciteren']	= get_post_meta( $post_id, 'siw_vacature_toelichting_solliciteren', true );
	$job_data['meervoud']					= get_post_meta( $post_id, 'siw_vacature_meervoud', true );

	return $job_data;
}


/**
 * [siw_get_featured_job description]
 * @return [type] [description]
 */
function siw_get_featured_job() {
	$meta_query = array(
		'relation'	=> 'AND',
		array(
			'key'		=> 'siw_vacature_deadline',
			'value'		=> time(),
			'compare'	=> '>=',
		),
		array(
			'key'		=> 'siw_vacature_uitgelicht',
			'value'		=> 'on',
			'compare'	=> '=',
		),
	);
	$query_args = array(
		'post_type'				=>	'vacatures',
		'posts_per_page'		=>	1,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_vacature_deadline',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query,
		'fields' 				=> 'ids'
	);
	$featured_job_id = get_posts( $query_args );
	if ( empty ( $featured_job_id ) ) {
		return false;
	}
	$featured_job = siw_get_job_data( $featured_job_id[0] );

	return $featured_job;
}
