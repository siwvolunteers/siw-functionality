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

	$show_topbar_event_days_range = siw_get_setting( 'topbar_event_days_range' );
	$show_topbar_days_before_event = (int) $show_topbar_event_days_range[2];
	$hide_topbar_days_before_event = (int) $show_topbar_event_days_range[1];

	$date_before = strtotime( date( 'Y-m-d' ) ) + ( $hide_topbar_days_before_event * DAY_IN_SECONDS );
	$date_after = strtotime( date( 'Y-m-d' ) ) + ( $show_topbar_days_before_event * DAY_IN_SECONDS );

	$upcoming_events = siw_get_upcoming_events( 1, $date_before, $date_after );

	if ( empty ( $upcoming_events ) ) {
		return false;
	}
	$event = $upcoming_events[0];

	if ( $event['start_date'] == $event['end_date'] ) {
		$link_text = sprintf( __( 'Kom naar de %s op %s.', 'siw' ), $event['title'], $event['date_range'] );
	}
	else {
		$link_text = sprintf( __( 'Kom naar de %s van %s.', 'siw' ), $event['title'], $event['date_range'] );
	}

	$topbar_event_content = array(
		'intro'		=> __( 'Maak kennis met SIW.', 'siw' ),
		'link_url'	=> $event['permalink'],
		'link_text'	=> $link_text,
	);

	return $topbar_event_content;
}
