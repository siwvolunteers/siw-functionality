<?php
/**
 * Functies m.b.t. landen
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Geeft array van landen terug op basis van zoekterm (slug of ISO-code)
 *
 * @param string $index
 * @param string $context all|workcamps|esc_projects|tailor_made_projects
 * @return SIW_Data_Country[]
 */
function siw_get_countries( string $context = 'all', string $index = 'slug' ) { 

	$countries = wp_cache_get( "{$context}_{$index}", 'siw_countries' );
	if ( false !== $countries ) {
		return $countries;
	}

	$continents = siw_get_data_file_ids( 'countries' );

	foreach ( $continents as $continent ) {
		$continent = str_replace( '_', '-', $continent );
		$continent_data[ $continent ] = siw_get_data( $continent, 'countries' );
	}
	
	// Continent toevoegen aan elke land en array platslaan
	$data = [];
	foreach ( $continent_data as $continent => $countries_data ) {
		$countries_data = array_map( function( $country_data ) use ( $continent ) {
			$country_data['continent'] = $continent;
			return $country_data;
		}, $countries_data );
		$data = array_merge( $data, $countries_data );
	}

	//Sorteren op naam
	usort( $data, function( $a, $b ) {
		return strnatcmp($a['name'], $b['name']);
	});

	$countries = [];
	foreach ( $data as $item ) {
		$country = new SIW_Data_Country( $item );
		if ( 'all' == $context 
			|| ( 'workcamps' == $context && true === $country->has_workcamps() )
			|| ( 'esc_projects' == $context && true === $country->has_esc_projects() )
			|| ( 'tailor_made_projects' == $context && true === $country->has_tailor_made_projects() )
		) {
			$countries[ $item[ $index ] ] = $country;
		}
	}
	wp_cache_set( "{$context}_{$index}", $countries, 'siw_countries' );

	return $countries;
}

/**
 * Geeft land terug op basis van zoekterm (slug of ISO-code)
 *
 * @param string $country
 * @param string $index
 * @return SIW_Data_Country
 */
function siw_get_country( string $country, string $index = 'slug' ) {

	$countries = siw_get_countries( 'all', $index  );
	if ( ! isset( $countries[ $country ] ) ) {
		return false;
	}
	return $countries[ $country ];
}
