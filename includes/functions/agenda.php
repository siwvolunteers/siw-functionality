<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Zet noindex voor evenementen die al begonnen zijn */
siw_add_cron_job( 'siw_set_noindex_for_past_events' );

add_action( 'siw_set_noindex_for_past_events', function() {
	$args = array(
		'post_type'			=> 'agenda',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$event_ids = get_posts( $args );
	foreach ( $event_ids as $event_id ) {
		$noindex = 0;
		$start_ts = get_post_meta( $event_id, 'siw_agenda_start', true );
		if ( $start_ts < time() ) {//TODO:vergelijken datum i.p.v. ts
			$noindex = 1;
		}
		siw_seo_set_noindex( $event_id, $noindex );
	}
} );


/**
 * Geeft array met gegevens van toekomstige evenementen terug
 *
 * @param  int $number
 * @param  string $min_date
 * @param  string $max_date
 *
 * @return array
 */
function siw_get_upcoming_events( $number, $min_date = '', $max_date = '' ) {

	if ( '' == $min_date ) {
		$min_date = strtotime( date( 'Y-m-d' ) );
	}

	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'siw_agenda_eind',
			'value'		=>	$min_date,
			'compare'	=>	'>='
		),

	);
	if ( '' != $max_date ) {
		$meta_query_args[] = array(
			'key'		=> 'siw_agenda_start',
			'value'		=> $max_date,
			'compare'	=>	'<='
		);
	}

	$query_args = array(
		'post_type'				=>	'agenda',
		'posts_per_page'		=>	$number,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_agenda_start',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query_args,
		'fields' 				=> 'ids'
	);

	$events_ids = get_posts( $query_args );

	$upcoming_events = array();
	foreach ( $events_ids as $event_id ) {
		$upcoming_events[] = siw_get_event_data( $event_id );
	}

	return $upcoming_events;
}

/* TODO: verwijderen oude evenmenten */



/**
 * Haal gegevens van agenda-evenement op
 *
 * @param int $post_id
 *
 * @return array
 */
function siw_get_event_data( $post_id ) {

	$start_ts	= get_post_meta( $post_id, 'siw_agenda_start', true );
	$end_ts 	= get_post_meta( $post_id, 'siw_agenda_eind', true );

	$event_data = array(
		'permalink'					=> get_permalink( $post_id ),
		'title'						=> get_the_title( $post_id ),
		'excerpt' 					=> get_the_excerpt( $post_id ),
		'post_thumbnail_url'		=> get_the_post_thumbnail_url( $post_id ), //TODO: is dit nog nodig?
		'start_date' 				=> date( 'Y-m-d', $start_ts ),
		'end_date'					=> date( 'Y-m-d', $end_ts ),
		'start_time'				=> date( 'H:i', $start_ts ),
		'end_time'					=> date( 'H:i', $end_ts ),
		'program' 					=> get_post_meta( $post_id, 'siw_agenda_programma', true ),
		'description'				=> get_post_meta( $post_id, 'siw_agenda_beschrijving', true ),
		'highlight_quote'			=> get_post_meta( $post_id, 'siw_agenda_highlight_quote', true ),
		'location'					=> get_post_meta( $post_id, 'siw_agenda_locatie', true ),
		'address'					=> get_post_meta( $post_id, 'siw_agenda_adres', true ),
		'postal_code'				=> get_post_meta( $post_id, 'siw_agenda_postcode', true ),
		'city'						=> get_post_meta( $post_id, 'siw_agenda_plaats', true ),
		'application' 				=> get_post_meta( $post_id, 'siw_agenda_aanmelden', true ),
		'application_explanation'	=> get_post_meta( $post_id, 'siw_agenda_aanmelden_toelichting', true ),
		'application_link_url'		=> get_post_meta( $post_id, 'siw_agenda_aanmelden_link_url', true ),
		'application_link_text' 	=> get_post_meta( $post_id, 'siw_agenda_aanmelden_link_tekst', true ),
		'text_after_hide_form'		=> get_post_meta( $post_id, 'siw_agenda_tekst_na_verbergen_formulier', true ),
	);
	$event_data['date_range'] = siw_get_date_range_in_text( $event_data['start_date'],  $event_data['end_date'] , false );
	$event_data['duration']	= sprintf( '%s, %s&nbsp;-&nbsp;%s', $event_data['date_range'], $event_data['start_time'], $event_data['end_time'] );


	return $event_data;
}
