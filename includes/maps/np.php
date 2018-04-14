<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_filter( 'mapplic_data', function ( $data, $id ) {

	if ( $id != siw_get_setting( 'np_map' ) ) {
		return $data;
	}

	/* Eigenschappen van categorie zetten */
	$data->categories = array();

	$category = new stdClass();
	$category->id = 'nl';
	$category->title = __( 'Nederlandse projecten', 'siw' );
	$category->color = SIW_PRIMARY_COLOR_HOVER;

	$data->categories[] = $category;

	/* Zet projecten in kaart */
	$data->levels[0]->locations = array();

	$projects = siw_get_dutch_projects();
	foreach ( $projects as $index => $project ) {

		$duration = siw_get_date_range_in_text( $project['start_date'], $project['end_date'] );
		$description = __( 'Data:', 'siw' ) . SPACE . $duration . BR;
		$description .= __( 'Deelnemers:', 'siw' ) . SPACE . $project['participants'] . BR;
		$description .= __( 'Soort werk:', 'siw' ) . SPACE . $project['work_name'] . BR;
		$description .= __( 'Locatie:', 'siw' ) . SPACE . $project['city'] . ', ' . __( 'provincie', 'siw' ) . SPACE . $project['province_name'];

		$location = new stdClass();
		$location->id = $index;
		$location->title = $project['name'];
		$location->about = $project['province_name'];
		$location->lat = $project['latitude'];
		$location->lng = $project['longitude'];
		$location->description = $description;
		$location->pin = 'circular pin-md pin-label';
		$location->category = 'nl';
		$location->action = 'tooltip';
		$location->fill = SIW_SECONDARY_COLOR;

		$data->levels[0]->locations[] = $location;
	}

	return $data;
}, 10, 2 );


/* Provincies inkleuren op basis van instellingen */
add_action( 'wp_enqueue_scripts', function() {

	//bepaal provincies van projecten
	$projects = siw_get_dutch_projects();
	$provinces = array();
	foreach ( $projects as $project ) {
		$provinces[] = sprintf( '#nl-%s path', $project['province'] );
	}
	$provinces = array_unique( $provinces );

	$selectors = implode( ',', $provinces );
	$primary_color_hover = SIW_PRIMARY_COLOR_HOVER;

	$inline_css = "
		$selectors{
			fill:$primary_color_hover;
		}
	";

	wp_add_inline_style( 'pinnacle_child', $inline_css );
}, 101 );
