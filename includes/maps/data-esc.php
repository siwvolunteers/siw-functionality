<?php
/**
 * Kaart van Europa met EVS-landen
 * 
 * @package SIW\Maps
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

siw_register_map( 'esc', __( 'ESC', 'siw' ), 'europe' );

add_filter( 'siw_map_esc_data', function( $map_data ) {

	/** Gegevens kaart */
	$map_data['data'] = [
		'mapwidth'  => 600,
		'mapheight' => 600,
	];

	/* Zoekoptie activeren */
	$map_data['options'] = [
		'search' => true,
	];

	/** Standaard-categorie */
	$map_data['categories'] = [
		[
			'id'    => 'bestemmingen',
			'title' =>  __( 'Bestemmingen', 'siw' ),
			'show'  => true,
			'legend' => true,
			'toggle' => true,
		],
	]; 

	/** EVS-landen */
	$countries = siw_get_countries( 'esc_projects' );
	foreach ( $countries as $country ) {
		$europe_map_data = $country->get_europe_map_data();
		$location = [
			'id'        => $europe_map_data->code,
			'title'     => $country->get_name(),
			'x'         => $europe_map_data->x,
			'y'         => $europe_map_data->y,
			'category'  => 'bestemmingen'
		];
		$map_data['locations'][] = $location;
	}

	return $map_data;
});