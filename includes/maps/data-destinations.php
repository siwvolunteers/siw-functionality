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
 */
function siw_generate_country_description( $country ) {

	$tailor_made_page_link = \siw_get_translated_page_link( siw_get_setting( 'op_maat_page' ) );
	$evs_page_link = \siw_get_translated_page_link( siw_get_setting( 'evs_page' ) );
	$workcamps_page_link = \siw_get_translated_page_link( siw_get_setting( 'workcamps_page' ) );

	/* Groepsprojecten */
	if ( true == $country->has_workcamps() ) {
		$workcamp_count = \siw_count_projects_by_term( 'pa_land', $country->get_slug() );
		if ( $workcamp_count > 0 ) {
			$url = get_term_link( $country->get_slug(), 'pa_land' );
			$text = __( 'Bekijk alle projecten', 'siw' );
		}
		else {
			$url = $workcamps_page_link;
			$text = __( 'Lees meer', 'siw' );
		}
		$project_types[] = esc_html__( 'Groepsprojecten', 'siw' ) . SPACE . \siw_generate_link( $url, $text );            
	}
	
	/* Op maat*/
	if ( true == $country->has_tailor_made_projects() ) {
		$project_types[] = esc_html__( 'Projecten Op Maat', 'siw' ) . SPACE . \siw_generate_link( $tailor_made_page_link, __( 'Lees meer', 'siw' ) );
	}
	
	/* EVS */
	if ( true == $country->has_evs_projects() ) {
		$project_types[] = esc_html__( 'EVS', 'siw' ) . SPACE . \siw_generate_link( $evs_page_link, __( 'Lees meer', 'siw' ) );
	}
	
	$description = esc_html__( 'In dit land bieden wij de volgende projecten aan:', 'siw' ) . \siw_generate_list( $project_types );

	return $description;
}