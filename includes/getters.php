<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met gewhiteliste IP-adressen terug
 *
 * @return array
 */
function siw_get_ip_whitelist() {
	for ( $x = 1 ; $x <= SIW_IP_WHITELIST_SIZE; $x++ ) {
		$ip_whitelist[] = siw_get_setting( "whitelist_ip_{$x}" );
	}
	return $ip_whitelist;
}


/**
 * Geeft de datum van de volgende EVS-deadline terug
 * @param bool $date_in_text
 * @return date
 */
function siw_get_next_evs_deadline( $date_in_text = false ) {
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_EVS_DEADLINES; $x++ ) {
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
		$next_evs_deadline = siw_get_date_in_text( $next_evs_deadline );
	}

	return $next_evs_deadline;
}


/**
 * Geeft de maand en jaar van het volgende EVS-vertrekmoment terug
 *
 * Telt 14 weken op bij de volgende EVS-deadline
 * @return date
 */
function siw_get_next_evs_departure_month() {

	$weeks = 14; //TODO: Moet dit flexibel zijn?

	$next_evs_deadline = siw_get_next_evs_deadline();
	if ( empty( $next_evs_deadline) ) {
		return;
	}

	$next_evs_departure = date_parse( date("Y-m-d", strtotime( $next_evs_deadline) + ( $weeks * WEEK_IN_SECONDS ) ) );
	$next_evs_departure_month = siw_get_month_in_text( 	$next_evs_departure['month'] ) . ' ' . $next_evs_departure['year'];
}


/**
 * Geeft de datum van de volgende infodag terug
 *
 * @param bool $date_in_text
 * @return date
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
 * @return array
 */
function siw_get_future_info_days( $dates_in_text = false ) {
	//TODO: maximaal aantal resultaten als parameter
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++ ) {
		$info_days[]= siw_get_setting("info_day_{$x}");
	}
	asort( $info_days );
	$hide_form_days_before_info_day = siw_get_setting( 'hide_application_form_days_before_info_day' );
	$limit = date("Y-m-d", time() + ( $hide_form_days_before_info_day * DAY_IN_SECONDS ));

	$future_info_days = array();
	foreach ( $info_days as $info_day ) {
		if ( $info_day > $limit ) {
			$future_info_days[] = $dates_in_text ? siw_get_date_in_text( $info_day, false ) : $info_day;
		}
	}

	return $future_info_days;
}


/**
 * Geeft de maand in tekst terug
 *
 * @param int $month maandnummer (1-12)
 * @return string
 */
function siw_get_month_in_text( $month ) {
	$month_to_text = array (
		'1'		=> 'januari',
		'2'		=> 'februari',
		'3'		=> 'maart',
		'4'		=> 'april',
		'5'		=> 'mei',
		'6'		=> 'juni',
		'7'		=> 'juli',
		'8'		=> 'augustus',
		'9'		=> 'september',
		'10'	=> 'oktober',
		'11'	=> 'november',
		'12'	=> 'december',
	);
	$month_in_text = ( isset ( $month_to_text[ $month ] ) ? $month_to_text[ $month ] : '' );

	return $month_in_text;
}


/**
 * Geeft de datum in tekst terug
 *
 * @param date $date Y-m-d
 * @param bool $year Jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_in_text( $date, $year = true ) {
	$date_array = date_parse( $date );
	$day = $date_array['day'];
	$month = siw_get_month_in_text( $date_array['month'] );
	$date_in_text = $day . ' ' . $month;
	if ( $year ) {
		$year = $date_array['year'];
		$date_in_text .=  ' ' . $year;
	}

	return $date_in_text;
}


/**
 * Geeft de datum in tekst terug
 *
 * @param date $date_start Y-m-d
 * @param date $date_end Y-m-d
 * @param bool $year jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_range_in_text( $date_start, $date_end, $year = true ) {
	//als beide datums gelijk zijn gebruik dan siw_get_date_in_text
	if ( $date_start == $date_end ) {
		$date_range_in_text = siw_get_date_in_text( $date_start, $year );
	}
	else {
		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		$date_range_in_text = $date_start_array['day'];
		if ( $date_start_array['month'] != $date_end_array['month']) {
			$date_range_in_text .= ' ' . siw_get_month_in_text( $date_start_array['month'] );
		}
		if ( ($date_start_array['year'] != $date_end_array['year'] ) and $year ) {
			$date_range_in_text .= ' ' . $date_start_array['year'];
		}
		$date_range_in_text .= ' t/m ';
		$date_range_in_text .= $date_end_array['day'];
		$date_range_in_text .= ' ' . siw_get_month_in_text( $date_end_array['month'] );
		if ( $year ) {
			$date_range_in_text .= ' ' . $date_end_array['year'];
		}

	}
	return $date_range_in_text;
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
	$month = ltrim( $month, '0' );
	$current_year = date( 'Y' );

	$month_name = ucfirst( siw_get_month_in_text( $month ) );
	if ( $year != $current_year ) {
		$month_name .= ' ' . $year;
	}
	return $month_name;
}


/**
 * Haal gegevens van agenda-evenement op
 *
 * @param int $post_id
 *
 * @return array
 */
function siw_get_event_data( $post_id ) {
	$start_ts 								= get_post_meta( $post_id, 'siw_agenda_start', true );
	$end_ts 								= get_post_meta( $post_id, 'siw_agenda_eind', true );
	$event_data['start_date'] 				= date("Y-m-d", $start_ts );
	$event_data['end_date'] 				= date("Y-m-d", $end_ts );
	$start_time								= date("H:i", $start_ts );
	$end_time								= date("H:i", $end_ts );
	$date_range								= siw_get_date_range_in_text( $event_data['start_date'],  $event_data['end_date'] , false );
	$event_data['duration']					= $date_range  . ', ' .  $start_time . '&nbsp;-&nbsp;' . $end_time;
	$event_data['program'] 					= get_post_meta( $post_id, 'siw_agenda_programma', true );
	$event_data['description']				= get_post_meta( $post_id, 'siw_agenda_beschrijving', true );
	$event_data['location']					= get_post_meta( $post_id, 'siw_agenda_locatie', true );
	$event_data['address']					= get_post_meta( $post_id, 'siw_agenda_adres', true );
	$event_data['postal_code']				= get_post_meta( $post_id, 'siw_agenda_postcode', true );
	$event_data['city']						= get_post_meta( $post_id, 'siw_agenda_plaats', true );

	$event_data['application'] 				= get_post_meta( $post_id, 'siw_agenda_aanmelden', true );
	$event_data['application_explanation']	= get_post_meta( $post_id, 'siw_agenda_aanmelden_toelichting', true );
	$event_data['application_link_url']		= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_url', true );
	$event_data['application_link_text'] 	= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_tekst', true );
	$event_data['text_after_hide_cd_form']	= get_post_meta( $post_id, 'siw_agenda_tekst_na_verbergen_formulier', true );

	return $event_data;
}


/**
 * Haal gegevens van vacature op
 *
 * @param int $post_id
 * @return array
 */
function siw_get_job_data( $post_id ) {
	$deadline_ts							= get_post_meta( $post_id, 'siw_vacature_deadline', true );
	$job_data['deadline_datum']				= date("Y-m-d", $deadline_ts );
	$job_data['deadline']					= siw_get_date_in_text( date("Y-m-d", $deadline_ts ), false);
	$job_data['inleiding']					= get_post_meta( $post_id, 'siw_vacature_inleiding', true );
	$job_data['wie_ben_jij']				= get_post_meta( $post_id, 'siw_vacature_wie_ben_jij', true );
	$job_data['wat_ga_je_doen']				= get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen', true );
	$job_data['wat_bieden_wij_jou']			= get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou', true );
	$job_data['contactpersoon_naam']		= get_post_meta( $post_id, 'siw_vacature_contactpersoon_naam', true );
	$job_data['contactpersoon_functie']	= get_post_meta( $post_id, 'siw_vacature_contactpersoon_functie', true );
	if ( $job_data['contactpersoon_naam'] ) {
		$job_data['contactpersoon_naam']	= $job_data['contactpersoon_naam'] . ' ( ' . $job_data['contactpersoon_functie'] . ' )';
	}
	$job_data['contactpersoon_email']		= antispambot( get_post_meta( $post_id, 'siw_vacature_contactpersoon_email', true ) );
	$job_data['contactpersoon_telefoon']	= get_post_meta( $post_id, 'siw_vacature_contactpersoon_telefoon', true );// Wordt nog niet gebruikt
	$job_data['solliciteren_naam']			= get_post_meta( $post_id, 'siw_vacature_solliciteren_naam', true );
	$job_data['solliciteren_functie']		= get_post_meta( $post_id, 'siw_vacature_solliciteren_functie', true );
	if (  $job_data['solliciteren_functie'] ) {
		 $job_data['solliciteren_naam'] =  $job_data['solliciteren_naam'] . ' ( ' .  $job_data['solliciteren_functie'] . ' )';
	}
	$job_data['solliciteren_email']			= antispambot( get_post_meta( $post_id, 'siw_vacature_solliciteren_email', true ) );
	$job_data['toelichting_solliciteren']	= get_post_meta( $post_id, 'siw_vacature_toelichting_solliciteren', true );
	$job_data['meervoud']					= get_post_meta( $post_id, 'siw_vacature_meervoud', true );

	return $job_data;
}
