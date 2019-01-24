<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft de datum van de volgende infodag terug
 *
 * @param bool $date_in_text
 * @return string
 */
function siw_get_next_info_day( $date_in_text = false ) {
	$future_info_days = siw_get_future_info_days( $date_in_text );

	if ( empty( $future_info_days ) ) {
		return;
	}
	$next_info_day = $future_info_days[0];

	return $next_info_day;
}


/**
 * Geeft de array met tokomstige infodagen terug
 *
 * @param bool $dates_in_text
 * @param int $results
 *
 * @return array
 */
function siw_get_future_info_days( $dates_in_text = false, $results = SIW_NUMBER_OF_INFO_DAYS ) {

	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++ ) {
		$info_days[]= siw_get_setting("info_day_{$x}");
	}
	asort( $info_days );
	$hide_form_days_before_info_day = siw_get_setting( 'hide_application_form_days_before_info_day' );
	$limit = date( 'Y-m-d', time() + ( $hide_form_days_before_info_day * DAY_IN_SECONDS ) );

	$future_info_days = array();
	foreach ( $info_days as $info_day ) {
		if ( $info_day > $limit ) {
			$future_info_days[] = $dates_in_text ? SIW_Formatting::format_date( $info_day, false ) : $info_day;
		}
	}

	$results = min( $results, SIW_NUMBER_OF_INFO_DAYS );
	$future_info_days = array_slice( $future_info_days, 0, $results );
	return $future_info_days;
}
