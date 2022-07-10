<?php declare(strict_types=1);

use SIW\Structured_Data\Event;
use SIW\Structured_Data\Event_Attendance_Mode;
use SIW\Structured_Data\Event_Status_Type;
use SIW\Structured_Data\NL_Non_Profit_Type;
use SIW\Structured_Data\Organization;
use SIW\Structured_Data\Place;
use SIW\Structured_Data\Postal_Address;
use SIW\Structured_Data\Virtual_Location;
use SIW\Properties;


/**
 * Functies m.b.t. evenementen
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */

/** Geeft toekomstige infodagen terug */
function siw_get_upcoming_info_days( int $number = 1 ): array {
	$args = [
		'number'   => $number,
		'info_day' => true,
	];
	return siw_get_upcoming_events( $args );
}

/** Geeft toekomstige infodagen terug */
function siw_get_upcoming_events( array $args = [] ): array {
	$args = wp_parse_args(
		$args,
		[
			'number'      => -1,
			'info_day'    => null,
			'online'      => null,
			'date_after'  => gmdate( 'Y-m-d' ),
			'date_before' => null,
			'return'      => 'ids',
		]
	);

	// Meta query opbouwen
	$meta_query = [
		'relation' => 'AND',
	];

	$meta_query[] = [
		[
			'key'     => 'event_date',
			'value'   => $args['date_after'],
			'compare' => '>',
		],
	];

	if ( null !== $args['date_before'] ) {
		$meta_query[] = [
			[
				'key'     => 'event_date',
				'value'   => $args['date_before'],
				'compare' => '<',
			],
		];
	}

	// Zoeken op infodag
	if ( null !== $args['info_day'] ) {
		$meta_query[] = [
			[
				'key'     => 'info_day',
				'value'   => $args['info_day'],
				'compare' => '=',
			],
		];
	}

	// Zoeken op online evenementen
	if ( null !== $args['online'] ) {
		$meta_query[] = [
			[
				'key'     => 'online',
				'value'   => $args['online'],
				'compare' => '=',
			],
		];
	}

	$post_query = [
		'post_type'      => 'siw_event',
		'posts_per_page' => $args['number'],
		'post_status'    => 'publish',
		'meta_key'       => 'event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => $meta_query,
		'fields'         => $args['return'],
	];

	return get_posts( $post_query );
}

/** Genereer structured data voor evenement */
function siw_generate_event_json_ld( int $event_id ): string {

	$event = Event::create()
		->set_name( get_the_title( $event_id ) )
		->set_description( siw_meta( 'abstract', [], $event_id ) )
		->set_start_date( new \DateTime( siw_meta( 'event_date', [], $event_id ) . siw_meta( 'start_time', [], $event_id ) ) )
		->set_end_date( new \DateTime( siw_meta( 'event_date', [], $event_id ) . siw_meta( 'end_time', [], $event_id ) ) )
		->set_url( get_the_permalink( $event_id ) );

	// Locatie toevoegen
	if ( siw_meta( 'online', [], $event_id ) ) {
		$event->set_event_attendance_mode( Event_Attendance_Mode::OnlineEventAttendanceMode() );
		$location = Virtual_Location::create()
			->set_url( get_the_permalink( $event_id ) ); // TODO: of externe aanmeldlink
	} else {
		$event->set_event_attendance_mode( Event_Attendance_Mode::OfflineEventAttendanceMode() );
		$location = siw_meta( 'location', [], $event_id );
		$location = Place::create()
			->set_name( $location['name'] )
			->set_address(
				Postal_Address::create()
					->set_street_address( $location['street'] . ' ' . $location['house_number'] )
					->set_address_locality( $location['city'] )
					->set_postal_code( $location['postcode'] )
					->set_address_country( 'NL' )
			);
	}
	$event->set_location( $location );

	// Organizer toevoegen
	$organizer = Organization::create();
	if ( siw_meta( 'different_organizer', [], $event_id ) ) {
		$organizer
			->set_name( siw_meta( 'organizer.name', [], $event_id ) )
			->set_url( siw_meta( 'organizer.url', [], $event_id ) );
	} else {
		$organizer
		->set_name( Properties::NAME )
		->set_same_as( SIW_SITE_URL )
		->set_logo( get_site_icon_url() )
		->set_non_profit_status( NL_Non_Profit_Type::NonprofitANBI() );
	}
	$event->set_organizer( $organizer );

	// Event status TODO:: meerdere statussen o.b.v. meta event_status
	$event->set_event_status( Event_Status_Type::EventScheduled() );

	return $event->to_script();
}
