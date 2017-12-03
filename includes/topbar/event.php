<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [siw_get_topbar_event_content description]
 * @return [type] [description]
 */
function siw_get_topbar_event_content() {
	$show_topbar_days_before_event = siw_get_setting( 'show_topbar_days_before_event' );
	$hide_topbar_days_before_event = siw_get_setting( 'hide_topbar_days_before_event' );

	$date_before = strtotime( date( 'Y-m-d' ) ) + ( $hide_topbar_days_before_event * DAY_IN_SECONDS );
	$date_after = strtotime( date( 'Y-m-d' ) ) + ( $show_topbar_days_before_event * DAY_IN_SECONDS );

	$upcoming_events = siw_get_upcoming_events( 1, $date_before, $date_after );

	if ( empty ( $upcoming_events ) ) {
		return;
	}
	$event = $upcoming_events[0];
	$topbar_event_content['intro'] = __( 'Maak kennis met SIW.', 'siw' );
	$topbar_event_content['link_url'] = $event['permalink'];

	if ( $event['start_date'] == $event['end_date'] ) {
		$topbar_event_content['link_text'] = sprintf( __( 'Kom naar de %s op %s', 'siw' ), $event['title'], $event['date_range'] );
	}
	else {
		$topbar_event_content['link_text'] = sprintf( __( 'Kom naar de %s van %s', 'siw' ), $event['title'], $event['date_range'] );
	}

	return $topbar_event_content;
}
