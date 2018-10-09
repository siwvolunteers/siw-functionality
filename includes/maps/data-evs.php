<?php
/**
 * Kaart van Europa met EVS-landen
 * 
 * @package SIW\Maps
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * */

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_map( 'evs', __( 'EVS', 'siw' ), 'europe' );

add_filter( 'siw_map_evs_data', function( $map_data ) {

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
        ],
    ]; 

    /** EVS-landen */
    $countries = get_countries( 'evs_projects' );
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