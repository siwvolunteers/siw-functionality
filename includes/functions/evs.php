<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft de datum van de volgende EVS-deadline terug
 * @param bool $date_in_text
 * @return string
 */
function siw_get_next_evs_deadline( $date_in_text = false ) {
	for ( $x = 1 ; $x <= SIW_Properties::NUMBER_OF_ESC_DEADLINES; $x++ ) {
		$evs_deadlines[]= siw_get_setting( "evs_deadline_{$x}" );
	}
	asort( $evs_deadlines );
	$weeks = siw_get_setting( 'evs_min_weeks_before_deadline' );
	$limit = date( 'Y-m-d', time() + ( $weeks * WEEK_IN_SECONDS ) );

	foreach ( $evs_deadlines as $evs_deadline => $evs_deadline_date ) {
		if ( $evs_deadline_date > $limit ) {
			$next_evs_deadline = $evs_deadline_date;
			break;
		}
	}
	if ( ! isset ( $next_evs_deadline ) ) {
		return;
	}

	if ( $date_in_text ) {
		$next_evs_deadline = SIW_Formatting::format_date( $next_evs_deadline );
	}

	return $next_evs_deadline;
}


/**
 * Geeft de maand en jaar van het volgende EVS-vertrekmoment terug
 *
 * Telt 14 weken op bij de volgende EVS-deadline
 * @return string
 */
function siw_get_next_evs_departure_month() {

	$weeks = SIW_Properties::ESC_WEEKS_BEFORE_DEPARTURE;
	$next_evs_deadline = siw_get_next_evs_deadline();

	if ( empty( $next_evs_deadline ) ) {
		return;
	}

	$next_evs_departure = strtotime( $next_evs_deadline) + ( $weeks * WEEK_IN_SECONDS ) ;
	$next_evs_departure_month = date_i18n( 'F Y',  $next_evs_departure );

	return $next_evs_departure_month;
}
