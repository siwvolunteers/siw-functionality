<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Vul EVS-kaart */
add_filter( 'mapplic_data', function ( $data, $id ) {

	if ( $id != siw_get_setting( 'evs_map' ) ) {
		return $data;
	}

	$data->zoomlimit = 1;

	/* Eigenschappen van categorie zetten */
	$data->categories = array();

	$category = new stdClass();
	$category->id = 'bestemmingen';
	$category->title = __( 'Bestemmingen', 'siw' );
	$category->color = SIW_PRIMARY_COLOR;

	$data->categories[] = $category;

	/* Landen toevoegen aan kaart */
	$countries = siw_get_countries_by_property( 'evs', true );

	$data->levels[0]->locations = array();
	foreach ( $countries as $country ) {
		$location = new stdClass();
		$location->id = $country['mapplic']['europe']['code'];
		$location->title = $country['name'];
		$location->x = $country['mapplic']['europe']['coordinates']['x'];
		$location->y = $country['mapplic']['europe']['coordinates']['y'];
		$location->lat = false;
		$location->lng = false;
		$location->description = false;
		$location->pin = 'hidden';
		$location->category = 'bestemmingen';
		$location->action = 'tooltip';
		$location->fill = SIW_PRIMARY_COLOR;
		$data->levels[0]->locations[] = $location;
	}

	return $data;
}, 10, 2 );