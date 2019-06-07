<?php
/**
 * Functies m.b.t. landen
 * 
 * @author      Maarten Bruna
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

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
 * @param string $context all|workcamps|esc_projects|tailor_made_projects
 * @return SIW_Country[]
 * 
 * @todo sorteren op naam
 */
function siw_get_countries( $context = 'all', $index = 'slug' ) { 
	
	$data = [];

	$continent_data = [];
	/**
	 * Array met gegevens van landen per continent
	 *
	 * @param array $continent_data Gegevens van landen per continent { iso|slug|name|allowed|workcamps|tailor_made|esc|europe_map|world_map}
	 */
	$continent_data = apply_filters( 'siw_country_data', $continent_data );
	
	/* Continent toevoegen aan elke land en array platslaan */
	foreach ( $continent_data as $continent => $countries_data ) {
		$countries_data = array_map( function( $country_data ) use ( $continent ) {
			$country_data['continent'] = $continent;
			return $country_data;
		}, $countries_data );
		$data = array_merge( $data, $countries_data );
	}

	usort( $data, function( $a, $b ) {
		return strnatcmp($a['name'], $b['name']);
	});


	$countries = [];
	foreach ( $data as $item ) {
		$country = new SIW_Country( $item );
		if ( 'all' == $context 
			|| ( 'workcamps' == $context && true == $country->has_workcamps() )
			|| ( 'esc_projects' == $context && true == $country->has_esc_projects() )
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
 * @return SIW_Country
 */
function siw_get_country( $country, $index = 'slug' ) {

	$countries = siw_get_countries( 'all', $index  );
	if ( ! isset( $countries[ $country ] ) ) {
		return false;
	}
	return $countries[ $country ];
}
