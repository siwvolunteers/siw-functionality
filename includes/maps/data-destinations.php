<?php
/**
 * Wereldkaart met aanbod per land
 * 
 * @package SIW\Maps
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

siw_register_map( 'destinations', __( 'Bestemmingen', 'siw' ), 'world' );

add_filter( 'siw_map_destinations_data', function( $map_data ) {

	/** Gegevens kaart */
	$map_data['data'] = [
		'mapwidth'  => 1200,
		'mapheight' => 760,
	];

	/* Zoekoptie activeren */
	$map_data['options'] = [
		'search' => true,
		'searchfields' => ['title', 'about', 'description'],
	];

	/* Zet de landen */
	$countries = siw_get_countries();
		
	foreach ( $countries as $country ) {
		if ( true != $country->is_allowed() ) {
			continue;
		}
		$continent = $country->get_continent();
		$world_map_data = $country->get_world_map_data();

		$location = [
			'id'            => $world_map_data->code,
			'title'         => $country->get_name(),
			'x'             => $world_map_data->x,
			'y'             => $world_map_data->y,
			'category'      => $continent->get_slug(),
			'fill'          => $continent->get_color(),
			'description'   => siw_generate_country_description( $country ),
		];
		$map_data['locations'][] = $location;
	}

	return $map_data;
});


/**
 * Genereer beschrijving van aanbod per land
 *
 * @param SIW_Country $country
 * @return string
 * 
 * @uses SIW_Formatting
 * @uses SIW_i18n
 * 
 * @todo verplaatsen naar SIW_Country?
 */
function siw_generate_country_description( $country ) {

	$tailor_made_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );
	$esc_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'esc_explanation_page' ) );
	$workcamps_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'workcamps_explanation_page' ) );

	/* Groepsprojecten */
	if ( true == $country->has_workcamps() ) {
		$country_term = get_term_by( 'slug', $country->get_slug(), 'pa_land' );
		if ( is_a( $country_term, 'WP_Term' ) ) {
			$workcamp_count = get_term_meta( $country_term->term_id, 'project_count', true );
		}
		else {
			$workcamp_count = 0;
		}

		if ( $workcamp_count > 0 ) {
			$url = get_term_link( $country->get_slug(), 'pa_land' );
			$text = __( 'Bekijk alle projecten', 'siw' );
		}
		else {
			$url = $workcamps_page_link;
			$text = __( 'Lees meer', 'siw' );
		}
		$project_types[] = esc_html__( 'Groepsprojecten', 'siw' ) . SPACE . SIW_Formatting::generate_link( $url, $text );
	}
	
	/* Op maat*/
	if ( true == $country->has_tailor_made_projects() ) {
		$project_types[] = esc_html__( 'Projecten Op Maat', 'siw' ) . SPACE . SIW_Formatting::generate_link( $tailor_made_page_link, __( 'Lees meer', 'siw' ) );
	}
	
	/* EVS */
	if ( true == $country->has_esc_projects() ) {
		$project_types[] = esc_html__( 'ESC', 'siw' ) . SPACE . SIW_Formatting::generate_link( $esc_page_link, __( 'Lees meer', 'siw' ) );
	}
	
	$description = esc_html__( 'In dit land bieden wij de volgende projecten aan:', 'siw' ) . SIW_Formatting::generate_list( $project_types );

	return $description;
}
