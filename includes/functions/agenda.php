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


/**
 * Haal gegevens van agenda-evenement op
 *
 * @param int $post_id
 *
 * @return array
 */
function siw_get_event_data( $post_id ) {
	$event_data['permalink']				= get_permalink( $post_id );
	$event_data['title']					= get_the_title( $post_id );
	$event_data['excerpt'] 					= get_the_excerpt( $post_id );
	$event_data['post_thumbnail_url'] 		= get_the_post_thumbnail_url( $post_id );
	$start_ts 								= get_post_meta( $post_id, 'siw_agenda_start', true );
	$end_ts 								= get_post_meta( $post_id, 'siw_agenda_eind', true );
	$event_data['start_date'] 				= date( 'Y-m-d', $start_ts );
	$event_data['end_date'] 				= date( 'Y-m-d', $end_ts );
	$event_data['start_time']				= date( 'H:i', $start_ts );
	$event_data['end_time']					= date( 'H:i', $end_ts );
	$event_data['date_range']				= siw_get_date_range_in_text( $event_data['start_date'],  $event_data['end_date'] , false );
	$event_data['duration']					= $event_data['date_range']	  . ', ' .  $event_data['start_time']	 . '&nbsp;-&nbsp;' . $event_data['end_time'];
	$event_data['program'] 					= get_post_meta( $post_id, 'siw_agenda_programma', true );
	$event_data['description']				= get_post_meta( $post_id, 'siw_agenda_beschrijving', true );
	$event_data['highlight_quote']			= get_post_meta( $post_id, 'siw_agenda_highlight_quote', true );
	$event_data['location']					= get_post_meta( $post_id, 'siw_agenda_locatie', true );
	$event_data['address']					= get_post_meta( $post_id, 'siw_agenda_adres', true );
	$event_data['postal_code']				= get_post_meta( $post_id, 'siw_agenda_postcode', true );
	$event_data['city']						= get_post_meta( $post_id, 'siw_agenda_plaats', true );
	$event_data['application'] 				= get_post_meta( $post_id, 'siw_agenda_aanmelden', true );
	$event_data['application_explanation']	= get_post_meta( $post_id, 'siw_agenda_aanmelden_toelichting', true );
	$event_data['application_link_url']		= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_url', true );
	$event_data['application_link_text'] 	= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_tekst', true );
	$event_data['text_after_hide_form']		= get_post_meta( $post_id, 'siw_agenda_tekst_na_verbergen_formulier', true );

	return $event_data;
}
