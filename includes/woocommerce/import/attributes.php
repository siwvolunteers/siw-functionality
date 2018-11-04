<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Genereer de postslug van een project
 *
 * @param string $code
 * @param string $country
 * @param string $work
 * @param date $date yyyy-mm-dd
 *
 * @return string
 */
function siw_get_workcamp_post_slug( $code, $country, $work, $end_date ) {
	$year =  date( 'Y', strtotime( $end_date ) );
	$title = siw_get_workcamp_title( $country, $work );
	return sprintf( '%s-%s-%s', $year, $code, sanitize_title( $title ) );
}



/**
 * Zet datum in yy-mm-dd om in dd-mm-yy
 *
 * @param date $date yyyy-mm-dd
 *
 * @return date dd-mm-yyy
 */
function siw_get_workcamp_formatted_date ( $date ) {
	if ( '1970-01-01' == $date ) {
		return '';
	}
	return date('j-n-Y', strtotime( $date ) );
}
//__return_empty_string()

/**
 * Zet datum om naar slug voor pa_maand
 *
 * @param date $date yyyy-mm-dd
 *
 * @return string yyyymm
 */
function siw_get_workcamp_month_slug ( $start_date ) {
	if ( '1970-01-01' == $start_date ) {
		return '';
	}
	return date( 'Ym', strtotime( $start_date ) );
}


/**
 * Bepaal de post status van het project (gepubliceerd/concept)
 *
 * @param  string $work
 * @param  string $country
 *
 * @return string
 */
function siw_get_workcamp_post_status( $work, $country ) {
	$post_status = 'publish';
	$work_array = explode( ',', $work );
	$allowed = siw_get_workcamp_country_allowed( $country );
	if ( in_array( 'KIDS', $work_array ) && 'yes' == $allowed ) {
		$post_status = 'draft';
	}
	return $post_status;
}


/**
 * Bepaal de duur van het project in dagen
 *
 * @param date $start_date yyyy-mm-dd
 * @param date $end_date yyyy-mm-dd
 *
 * @return int
 */
function siw_get_workcamp_duration_in_days( $start_date, $end_date ) {
	$start_date = strtotime( $start_date );
	$end_date = strtotime( $end_date );
	$duration_in_seconds = $end_date - $start_date;
	$duration_in_days = round( $duration_in_seconds / DAY_IN_SECONDS );
	return $duration_in_days;
}


/**
 * Geeft continent-slug o.b.v. code terug
 *
 * @param string $country_code
 *
 * @return string
 */
function siw_get_workcamp_continent_slug( $country_code ) {
	$continent_slug = '';
	$country = siw_get_country( $country_code );
	if ( isset ( $country['continent'] ) ) {
		$continent_slug = $country['continent'];
	}
	return $continent_slug;
}


/**
 * Geeft land-slug o.b.v. code terug
 *
 * @param string $country_code
 *
 * @return string
 */
function siw_get_workcamp_country_slug( $country_code ) {
	$country_slug = '';
	$country = siw_get_country( $country_code );
	if ( isset ( $country['slug'] ) ) {
		$country_slug = $country['slug'];
	}
	return $country_slug;
}


/**
 * Geeft landnaam o.b.v. code terug
 *
 * @param string $country_code
 *
 * @return string
 */
function siw_get_workcamp_country_name( $country_code ) {
	$country_name = '';
	$country = siw_get_country( $country_code );
	if ( isset ( $country['name']) ) {
		$country_name = $country['name'];
	}
	return $country_name;
}


/**
 * Geeft terug of land toegestaan is
 *
 * @param string $country_code
 *
 * @return string
 */
function siw_get_workcamp_country_allowed( $country_code ) {

	$country = siw_get_country( $country_code );
	if ( isset ( $country['allowed'] ) ) {
		$country_allowed = $country['allowed'];
	}
	else {
		$country_allowed = 'no';
	}
	return $country_allowed;
}


/**
 * Geeft de slugs van de soorten werk van het project terug
 *
 * @param string $work
 * @param bool $implode
 *
 * @return array
 */
function siw_get_workcamp_work_slugs( $work, $implode = true ) {
	$work_types = siw_get_project_work_types();
	$work_slugs = explode( ',', $work );
	foreach ( $work_slugs as $key=>$work_slug ) {
		$work_slugs[ $key ] = isset( $work_types[ $work_slug ] ) ? $work_types[ $work_slug ] : null;
	}
	return ( $implode ) ? implode( '|', $work_slugs ) : $work_slugs;
}


/**
 * Geeft een zin met het soort werk terug
 *
 * @param string $work
 * @param bool $single
 *
 * @return array
 */
function siw_get_workcamp_work_in_text( $work, $single = true ) {
	$work_slugs = siw_get_workcamp_work_slugs( $work, false );
	$work_in_text = $work_slugs[0];
	if ( isset ( $work_slugs[1]) && ! $single ) {
		$work_in_text .= ' en ' . $work_slugs[1];
	}
	return $work_in_text;
}


/**
 * Geeft de geformatterde lokale bijdrage terug
 *
 * @param int $participation_fee
 * @param string $participation_fee_currency
 *
 * @return string
 */
function siw_get_workcamp_local_fee( $participation_fee, $participation_fee_currency ) {

	$participation_fee = (int) $participation_fee;

	if ( 0 == $participation_fee || ! is_string( $participation_fee_currency ) ) {
		return '';
	}

	$currency = siw_get_currency( $participation_fee_currency );

	if ( $currency && 'EUR' != $participation_fee_currency ) {
		$local_fee = sprintf( '%s %d (%s)', $currency->get_symbol(), $participation_fee, $currency->get_name() );
	}
	elseif( 'EUR' == $participation_fee_currency ) {
		$local_fee = sprintf( '&euro; %s', $participation_fee );
	}
	else {
		$local_fee = sprintf( '%s %d', $participation_fee_currency, $participation_fee );
	}
	return $local_fee;
}


/**
 * Geeft het geformatteerde aantal vrijwilligers terug
 *
 * @param int $numvol
 * @param int $numvol_m
 * @param int $numvol_f
 *
 * @return string
 */
function siw_get_workcamp_number_of_volunteers( $numvol, $numvol_m, $numvol_f ) {
	$numvol_m = (integer) $numvol_m;
	$numvol_f = (integer) $numvol_f;
	$numvol = (integer) $numvol;

	$male_label = ( 1 == $numvol_m ) ? 'man' : 'mannen';
	$female_label = ( 1 == $numvol_f ) ? 'vrouw' : 'vrouwen';

	if ( $numvol == ($numvol_m + $numvol_f) ){
		$number_of_volunteers = sprintf( '%d (%d %s en %d %s)', $numvol, $numvol_m, $male_label, $numvol_f, $female_label );
	}
	else {
		$number_of_volunteers = $numvol;
	}
	return $number_of_volunteers;
}


/**
 * Geeft de slugs van de projecttalen terug
 *
 * @param string $languages
 *
 * @return string
 */
function siw_get_workcamp_languages_slugs( $languages ) {
	$language_codes = explode( ',', $languages );
	$project_languages = siw_get_project_languages();
	$languages = '';
	foreach ( $language_codes as $code ) {
		$code = strtoupper( $code );
		if ( isset( $project_languages[ $code ] ) ){
			$languages .= $project_languages[ $code ] . '|';
		}
	}
	return $languages;

}


/**
 * Bepaal op basis van soort werk of VOG benodigd is
 *
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_is_vog_required( $work ) {
	$work_array = explode( ',', $work );
	$is_vog_required = '';
	if ( in_array( 'KIDS', $work_array ) ) {
		$is_vog_required = 'Ja';
	}
	return $is_vog_required;
}


/**
 * Bepaal of het project een familieproject is
 *
 * @param string $project_type
 * @param bool $family
 *
 * @return bool
 */
function siw_get_workcamp_is_family_project( $project_type, $family ) {
	$family = (bool) $family;
	$family_project = false;
	if ( $family || 'FAM' == $project_type ) {
		$family_project = true;
	}
	return $family_project;
}


/**
 * Bepaal standaardtarief op basis van maximumleeftijd
 *
 * @param  int $max_age
 * @return string
 */
function siw_get_workcamp_default_tariff( $max_age ){
	$max_age = (int) $max_age;
	$default_tariff = 'regulier';
	if ( 18 > $max_age ) {
		$default_tariff = 'student';
	}
	return $default_tariff;
}


/**
 * Bepaal of het project een tienerproject is
 *
 * @param string $project_type
 * @param int $min_age
 * @param int $max_age
 *
 * @return bool
 */
function siw_get_workcamp_is_teenager_project( $project_type, $min_age, $max_age ) {
	$min_age = (int) $min_age;
	$max_age = (int) $max_age;
	$teenage_project = false;
	if ( ( $min_age < 17 && $min_age > 12 && $max_age < 20 ) || 'TEEN' == $project_type ) {
		$teenage_project = true;
	}
	return $teenage_project;
}


/**
 * Bepaal de doelgroep van het project (indien van toepassing)
 *
 * @param string $project_type
 * @param int $min_age
 * @param int $max_age
 * @param string $family
 *
 * @return string
 */
function siw_get_workcamp_target_audience( $project_type, $min_age, $max_age, $family ) {
	$family_project = siw_get_workcamp_is_family_project( $project_type, $family );
	$teenager_project = siw_get_workcamp_is_teenager_project( $project_type, $min_age, $max_age );

	$target_audience = array();
	if ( $family_project ) {
		$target_audience[] = 'familie|';
	}
	if ( $teenager_project ) {
		$target_audience[] = 'tieners|';
	}

	return implode( '|', $target_audience );
}


/**
 * Bepaal producttags van het project voor WooCommerce
 *
 * @param string $country
 * @param string $work
 * @param type  $project_type
 * @param int  $min_age
 * @param int $max_age
 * @param bool $family
 *
 * @return string
 */
function siw_get_workcamp_tags( $country, $work, $project_type, $min_age, $max_age, $family ) {
	$country = siw_get_workcamp_country_slug( $country );
	$work = siw_get_workcamp_work_slugs( $work );
	$target_audience = siw_get_workcamp_target_audience( $project_type, $min_age, $max_age, $family );

	$tags = $country . '|' . $work . '|' . $target_audience;
	return $tags;
}


/**
 * Bepaal de leeftijd-range van het project
 *
 * @param int $min_age
 * @param int $max_age
 *
 * @return string
 */
function siw_get_workcamp_age_range( $min_age, $max_age ) {
	$min_age = (int) $min_age;
	$max_age = (int) $max_age;
	if ( $min_age < 1 ) {
		$min_age = 18;
	}
	if ( $max_age < 1 ) {
		$max_age = 99;
	}
	$age_range = sprintf( '%d t/m %d jaar', $min_age, $max_age );

	return $age_range;
}


/**
 * Bepaal of er nog vrije plaatsen op een project zijn
 *
 * @param int $free_m
 * @param int $free_f
 *
 * @return string
 */
function siw_get_workcamp_free_places_left( $free_m, $free_f ) {
	$free_m = (int) $free_m;
	$free_f = (int) $free_f;

	return ( ( $free_m + $free_f ) > 0 ) ? 'yes' : 'no';
}
