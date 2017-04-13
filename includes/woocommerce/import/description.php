<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Geeft titel voor project terug
 *
 * @param string $country
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_title( $country, $work ) {
	$country_name = siw_get_workcamp_country_name( $country );
	$work = siw_get_workcamp_work_in_text( $work, false );
	$project_name = sprintf( '%s | %s', $country_name, ucfirst( $work ) );
	return $project_name;
}


/**
 * Geeft beschrijving voor project terug
 *
 * @param string $country
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_description( $descr_work, $descr_accomodation_and_food, $descr_location_and_leisure, $descr_partner, $descr_requirements, $notes, $description ) {
	//TODO: formattering herkennen en aanpassen voor bijvoorbeeld links en bulletlijsten
	$project_description = '\[accordion]';
	if ( strlen( $description ) > 3) {
		$project_description .= '\[pane title="Beschrijving"]' . esc_html( $description ) . '\[/pane]';
	}
	if ( strlen( $descr_work ) > 3) {
		$project_description .= '\[pane title="Werk"]' . esc_html( $descr_work ) . '\[/pane]';
	}
	if ( strlen( $descr_accomodation_and_food ) > 3) {
		$project_description .= '\[pane title="Accommodatie en maaltijden"]' . esc_html( $descr_accomodation_and_food ) . '\[/pane]';
	}
	if ( strlen( $descr_location_and_leisure ) > 3) {
		$project_description .= '\[pane title="Locatie en vrije tijd"]' . esc_html( $descr_location_and_leisure ) . '\[/pane]';
	}
	if ( strlen( $descr_partner ) > 3) {
		$project_description .= '\[pane title="Organisatie"]' . esc_html( $descr_partner ) . '\[/pane]';
	}
	if ( strlen( $descr_requirements ) > 3) {
		$project_description .= '\[pane title="Vereisten"]' . esc_html( $descr_requirements ). '\[/pane]';
	}
	if ( strlen( $notes ) > 3) {
		$project_description .= '\[pane title="Opmerkingen"]' . esc_html( $notes ) . '\[/pane]';
	}
	$project_description .= '\[/accordion]';
	return $project_description;
}




/**
 * Genereert projectsamenvatting o.b.v. projecteigenschappen
 *
 * @param  string $project_type
 * @param  string $country
 * @param  string $work
 * @param  date $start_date
 * @param  date $end_date
 * @param  int $numvol
 * @param  int $min_age
 * @param  int $max_age
 * @param  bool $family
 *
 * @return void
 */
function siw_get_workcamp_summary( $project_type, $country, $work, $start_date, $end_date, $numvol, $min_age, $max_age, $family ) {

	//verzamelen gegevens voor samenvatting
	$other_volunteers = ( (integer) $numvol ) - 1 ;
	$age_range_in_text = siw_get_workcamp_age_range( $min_age, $max_age );
	$work = siw_get_workcamp_work_in_text( $work, false );
	$project_duration_in_days = siw_get_workcamp_duration_in_days( $start_date, $end_date );
	$project_duration_in_text = siw_get_date_range_in_text( $start_date, $end_date, false );
	$teenager_project = siw_get_workcamp_is_teenager_project( $project_type, $min_age, $max_age );
	$family_project = siw_get_workcamp_is_family_project( $project_type, $family );

	//genereren samenvatting
	$project_summary = '';
	if ( $teenager_project ) {
		$project_summary .= sprintf( 'Dit is een tienerproject (%s).', $age_range_in_text );
	}
	else if ( $family_project ) {
		$project_summary .= 'Dit is een familieproject. ';
	}
	$project_summary .= sprintf( 'Samen met %d andere vrijwilligers ga je voor %d dagen naar een %sproject.<br/>', $other_volunteers, $project_duration_in_days, $work);
	$project_summary .= sprintf( 'Het project duurt van %s.', $project_duration_in_text );

	return $project_summary;
}


/**
 * Geeft SEO-titel voor project terug
 *
 * Format: SIW Vrijwilligerswerk | [werk]-project in [land]
 *
 * @param string $country
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_seo_title( $country, $work ) {
	$work = siw_get_workcamp_work_in_text( $work );
	$country = siw_get_workcamp_country_name( $country );
	$seo_title = sprintf( 'SIW Vrijwilligerswerk | %sproject in %s', ucfirst( $work ), $country );
 	return $seo_title;
}


/**
 * Geeft SEO-omschrijving voor project terug
 *
 * @param date $start_date
 * @param date $end_date
 * @param string $country
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_seo_description( $project_type, $country, $work, $start_date, $end_date, $numvol, $min_age, $max_age, $family ) {
	$seo_summary = '';
	$project_duration_in_text = siw_get_date_range_in_text( $start_date, $end_date, false );
	$project_summary = '';
	//TODO
	$seo_summary = sprintf( 'Van %s</br>', $project_duration_in_text );
	$seo_summary .= $project_summary;
	return $seo_summary;
}
