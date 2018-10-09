<?php
/**
 * Functies m.b.t. landen
 * 
 * @author      Maarten Bruna
 * @package 	SIW\Reference data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-country.php' );
require_once( __DIR__ . '/data-asia.php' );
require_once( __DIR__ . '/data-africa.php' );
require_once( __DIR__ . '/data-europe.php' );
require_once( __DIR__ . '/data-latin-america.php' );
require_once( __DIR__ . '/data-north-america.php' );


/**
 * Geeft array van landen terug op basis van zoekterm (slug of ISO-code)
 *
 * @param string $index
 * @param string $context all|workcamps|evs_projects|tailor_made_projects
 * @return \SIW_Country[]
 */
function get_countries( $context = 'all', $index = 'slug' ) { 
	
	$data = [];
	$continent_data = apply_filters( 'siw_country_data', [] );
	
	/* Continent toevoegen aan elke land en array platslaan */
	foreach ( $continent_data as $continent => $countries_data ) {
		$countries_data = array_map( function( $country_data ) use ( $continent ) {
			$country_data['continent'] = $continent;
			return $country_data;   
		}, $countries_data );
		$data = array_merge( $data, $countries_data );
	}

	//TODO: sorteren ?

	foreach ( $data as $item ) {
		$country = new \SIW_Country( $item );
		if ( 'all' == $context 
			|| ( 'workcamps' == $context && true == $country->has_workcamps() )
			|| ( 'evs_projects' == $context && true == $country->has_evs_projects() )
			|| ( 'tailor_made_projects' == $context && true == $country->has_tailor_made_projects() )
		) {
			$countries[ $item[ $index ] ] = $country;
		}
	}

	return $countries;
}


/**
 * Geeft land terug op basis van zoekterm (slug of ISO-code)
 *
 * @param string $country
 * @param string $index
 * @return \SIW_Country
 */
function get_country( $country, $index = 'slug' ) {

	$countries = get_countries( 'all', $index  );
	if ( ! isset( $countries[ $country ] ) ) {
		return false;
	}
	return $countries[ $country ];
}
