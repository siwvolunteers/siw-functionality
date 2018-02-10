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
	$deadline_ts			= get_post_meta( $post_id, 'siw_vacature_deadline', true );
	$contactpersoon_functie	= get_post_meta( $post_id, 'siw_vacature_contactpersoon_functie', true );
	$solliciteren_functie	= get_post_meta( $post_id, 'siw_vacature_solliciteren_functie', true );

	$job_data = array(
		'permalink'					=> get_permalink( $post_id ),
		'title'						=> get_the_title( $post_id ),
		'deadline_datum'			=> date( 'Y-m-d', $deadline_ts ),
		'deadline'					=> siw_get_date_in_text( date( 'Y-m-d', $deadline_ts ), false ),
		'inleiding'					=> get_post_meta( $post_id, 'siw_vacature_inleiding', true ),
		'highlight_quote'			=> get_post_meta( $post_id, 'siw_vacature_highlight_quote', true ),
		'uur_per_week'				=> get_post_meta( $post_id, 'siw_vacature_uur_per_week', true ),
		'wie_ben_jij'				=> get_post_meta( $post_id, 'siw_vacature_wie_ben_jij', true ),
		'wie_ben_jij_lijst'			=> get_post_meta( $post_id, 'siw_vacature_wie_ben_jij_lijst', true ),
		'wat_ga_je_doen'			=> get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen', true ),
		'wat_bieden_wij_jou'		=> get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou', true ),
		'contactpersoon_naam'		=> get_post_meta( $post_id, 'siw_vacature_contactpersoon_naam', true ),
		'contactpersoon_email'		=> antispambot( get_post_meta( $post_id, 'siw_vacature_contactpersoon_email', true ) ),
		'contactpersoon_telefoon'	=> get_post_meta( $post_id, 'siw_vacature_contactpersoon_telefoon', true ),// Wordt nog niet gebruikt
		'solliciteren_naam'			=> get_post_meta( $post_id, 'siw_vacature_solliciteren_naam', true ),
		'solliciteren_email'		=> antispambot( get_post_meta( $post_id, 'siw_vacature_solliciteren_email', true ) ),
		'toelichting_solliciteren'	=> get_post_meta( $post_id, 'siw_vacature_toelichting_solliciteren', true ),
		'meervoud'					=> get_post_meta( $post_id, 'siw_vacature_meervoud', true ),
	);

	if ( $contactpersoon_functie ) {
		$job_data['contactpersoon_naam']	= $job_data['contactpersoon_naam'] . ' (' . $contactpersoon_functie . ')';
	}

	if (  $solliciteren_functie ) {
		$job_data['solliciteren_naam'] 		=  $job_data['solliciteren_naam'] . ' (' .  $solliciteren_functie . ')';
	}


	return $job_data;
}


/**
 * Geeft eerste uitgelichte vacature terug
 * @return array
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
