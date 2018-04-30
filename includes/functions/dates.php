<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft de datum in tekst terug
 *
 * @param string $date Y-m-d
 * @param bool $year Jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_in_text( $date, $year = true ) {
	$format = $year ? 'j F Y' :  'j F';
	$date_in_text = date_i18n( $format, strtotime( $date ) );

	return $date_in_text;
}
add_filter( 'siw_date_in_text', function( $text, $date, $year = true ) {
	return siw_get_date_in_text( $date, $year );
}, 10, 3 );


/**
 * Geeft de datumrange in tekst terug
 *
 * @param string $date_start Y-m-d
 * @param string $date_end Y-m-d
 * @param bool $year jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_range_in_text( $date_start, $date_end, $year = true ) {
	//als beide datums gelijk zijn gebruik dan siw_get_date_in_text
	if ( $date_start == $date_end ) {
		return siw_get_date_in_text( $date_start, $year );
	}

	$date_start_array = date_parse( $date_start );
	$date_end_array = date_parse( $date_end );

	$format_end = $year ? 'j F Y' :  'j F';
	if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
		$format_start = 'j F Y';
	}
	elseif ( $date_start_array['month'] != $date_end_array['month'] ) {
		$format_start = 'j F';
	}
	else {
		$format_start = 'j';
	}

	$date_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
	$date_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

	$date_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $date_start_in_text, $date_end_in_text );

	return $date_range_in_text;
}
add_filter( 'siw_date_range_in_text', function( $text, $date_start, $date_end, $year = true ) {
	return siw_get_date_range_in_text( $date_start, $date_end, $year );
}, 10, 3 );



/**
 * Berekent leeftijd in jaren o.b.v. huidige datm
 * @param  string $date dd-mm-jjjj
 * @return int leeftijd in jaren
 */
function siw_get_age_from_date( $date ) {

	$from = new DateTime( $date );
	$to   = new DateTime('today');
	$age = $from->diff($to)->y;

	return $age;
}


/**
 * Zet pa_maand-slug om naar string
 *
 * @param string $slug
 *
 * @return string
 */
function siw_get_month_name_from_slug( $slug ) {
	$year = substr( $slug, 0, 4);
	$month = substr( $slug, 4, 2);
	$current_year = date( 'Y' );

	$date = sprintf( '1-%s-%s', $month, $year );

	$date_format = ( $year != $current_year ) ? 'F Y' : 'F';
	$month_name = ucfirst( date_i18n( $date_format, strtotime( $date ) ) ); //TODO:waarom ucfirst hier al uitvoeren?

	return $month_name;
}


/**
 * Geeft de maand in tekst terug
 *
 * @param string $date Y-m-d
 * @param bool $year Jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_month_in_text( $date, $year = true ) {
	$format = $year ? 'F Y' :  'F';
	$month_in_text = date_i18n( $format, strtotime( $date ) );

	return $month_in_text;
}


/**
 * Geeft de maand in tekst terug
 *
 * @param string $date_start Y-m-d
 * @param string $date_end Y-m-d
 * @param bool $year jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_month_range_in_text( $date_start, $date_end, $year = true ) {

	$date_start_array = date_parse( $date_start );
	$date_end_array = date_parse( $date_end );

	if ( $date_start == $date_end || $date_start_array['month'] == $date_end_array['month'] ) {
		return siw_get_month_in_text( $date_start, $year );
	}

	$format_end = $year ? 'F Y' :  'F';
	if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
		$format_start = 'F Y';
	}
	else {
		$format_start = 'F';
	}


	$month_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
	$month_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

	$month_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $month_start_in_text, $month_end_in_text );

	return $month_range_in_text;
}


/**
 * Undocumented function
 *
 * @param int $timestamp
 * @return int
 */
function siw_get_timestamp_in_gmt( $timestamp ) {

	$timestamp_in_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $timestamp ) ) . ' GMT' ); 
	return $timestamp_in_gmt;
}
