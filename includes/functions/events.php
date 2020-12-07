<?php

use SIW\Formatting;
use SIW\Properties;

/**
 * Functies m.b.t. evenementen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */


/**
 * Geeft toekomstige infodagen terug
 *
 * @param int $number
 *
 * @return array
 */
function siw_get_upcoming_info_days( int $number = 1 ) : array {
	$args = [
		'number'   => $number,
		'info_day' => true,
		
	];
	return siw_get_upcoming_events( $args );
}

/**
 * Geeft toekomstige infodagen terug
 *
 * @param array $args
 *
 * @return array
 */
function siw_get_upcoming_events( array $args = [] ) : array {
	$args = wp_parse_args(
		$args,
		[
			'number'      => -1,
			'info_day'    => null,
			'online'      => null,
			'date_after'  => date( 'Y-m-d' ),
			'date_before' => null,
			'return'      => 'ids'
		]
	);

	//Meta query opbouwen
	$meta_query = [
		'relation' => 'AND',
	];

	$meta_query[] = [
		[
			'key'     => 'event_date',
			'value'   => $args['date_after'],
			'compare' => '>'
		],
	];

	if ( null !== $args['date_before'] ) {
		$meta_query[] = [
			[
				'key'     => 'event_date',
				'value'   => $args['date_before'],
				'compare' => '<'
			],
		];
	}

	//Zoeken op infodag
	if ( null !== $args['info_day'] ) {
		$meta_query[] = [
			[
				'key'     => 'info_day',
				'value'   => $args['info_day'],
				'compare' => '=',
			]
		];
	}

	//Zoeken op online evenementen
	if ( null !== $args['online'] ) {
		$meta_query[] = [
			[
				'key'     => 'online',
				'value'   => $args['online'],
				'compare' => '=',
			]
		];
	}

	$post_query = [
		'post_type'           => 'siw_event',
		'posts_per_page'      => $args['number'],
		'post_status'         => 'publish',
		'meta_key'            => 'event_date',
		'orderby'             => 'meta_value',
		'order'               => 'ASC',
		'meta_query'          => $meta_query,
		'fields'              => $args['return']
	];

	return get_posts( $post_query );
}

/**
 * Genereer structured data voor evenement
 *
 * @param array $event_id
 * @return string
 * 
 * @todo verplaatsen naar Util/JsonLD
 */
function siw_generate_event_json_ld( int $event_id ) : string {

	$data = [
		'@context'      => 'http://schema.org',
		'@type'         => 'event',
		'name'          => esc_attr( get_the_title( $event_id ) ),
		'description'   => esc_attr( get_the_excerpt( $event_id ) ),
		'startDate'     => esc_attr(
			wp_date(
				'c',
				strtotime(
					siw_meta( 'event_date', [], $event_id ) . siw_meta( 'start_time', [], $event_id )
				)
			)
		),
		'endDate'       => esc_attr(
			wp_date(
				'c',
				strtotime(
					siw_meta( 'event_date', [], $event_id ) . siw_meta( 'end_time', [], $event_id )
				)
			)
		), 
		'url'           => esc_url( get_the_permalink( $event_id ) ),
	];

	//Locatie
	if ( siw_meta( 'online', [], $event_id ) ) {
		$online_location = siw_meta( 'online_location', [], $event_id );

		$data['eventAttendanceMode'] = 'https://schema.org/OnlineEventAttendanceMode';
		$data['location'] = [ 
			'@type' => 'VirtualLocation',
			'url'   => $online_location['url'],
		];
	}
	else {
		$location = siw_meta( 'location', [], $event_id );

		$data['eventAttendanceMode'] = 'https://schema.org/OfflineEventAttendanceMode';
		$data['location'] = [
			'@type'     => 'Place',
			'name'      => esc_attr( $location['name'] ),
			'address'   => esc_attr(
				sprintf(
					'%s %s, %s %s',
					$location['street'],
					$location['house_number'],
					$location['postcode'],
					$location['city']
				)
			),
		];
	}

	//Organizer toevoegen
	if ( siw_meta( 'different_organizer', [], $event_id ) ) {
		$data['organizer'] = [
			'@type' => 'Organization',
			'name'  => siw_meta( 'organizer.name', [], $event_id ),
			'url'   => siw_meta( 'organizer.url', [], $event_id ),
		];
	}
	else {
		$data['organizer'] = [
			'@type' => 'Organization',
			'name'  => Properties::NAME,
			'url'   => SIW_SITE_URL,
		];
	}

	//event status TODO: meta toevoegen / automatisch afleiden
	$statuses = [ 
		'scheduled'    => 'https://schema.org/EventScheduled',
		'cancelled'    => 'https://schema.org/EventCancelled',
		'moved_online' => 'https://schema.org/EventMovedOnline',
		'postponed'    => 'https://schema.org/EventPostponed',
		'rescheduled'  => 'https://schema.org/EventRescheduled',
	];

	$event_status = siw_meta( 'event_status', [], $event_id );
	if ( is_array( $event_status) ) {
		foreach( $event_status as $status ) {
			$data['eventStatus'][] = $statuses[ $status ];
		}
	}
	else {
		$data['eventStatus'][] = 'https://schema.org/EventScheduled';
	}
	return Formatting::generate_json_ld( $data );
}
