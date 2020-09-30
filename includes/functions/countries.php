<?php declare(strict_types=1);

/**
 * Functies m.b.t. landen
 * 
 * @copyright SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Country;

/**
 * Geeft array van landen terug op basis van zoekterm (slug of ISO-code)
 * 
 * @since     3.0.0
 *
 * @param string $index
 * @param string $context all|workcamps|esc_projects|tailor_made_projects|allowed|{continent_slug}
 * @param string $return objects|array
 * 
 * @return Country[]|array
 */
function siw_get_countries( string $context = 'all', string $index = 'slug', string $return = 'objects' ) { 

	$countries = wp_cache_get( "{$context}_{$index}_{$return}", 'siw_countries' );
	if ( false !== $countries ) {
		return $countries;
	}

	$continents = siw_get_data_file_ids( 'countries' );

	foreach ( $continents as $continent ) {
		$continent = str_replace( '_', '-', $continent );
		$continent_data[ $continent ] = siw_get_data( "countries/{$continent}" );
	}
	
	// Continent toevoegen aan elke land en array platslaan TODO: netter + refactor
	$data = [];
	foreach ( $continent_data as $continent => $countries_data ) {
		$countries_data = array_map( function( $country_data ) use ( $continent ) {
			$country_data['continent'] = $continent;
			return $country_data;
		}, $countries_data );
		$data = array_merge( $data, $countries_data );
	}

	//Sorteren op naam
	$data = wp_list_sort( $data, 'name' );

	//Zet index van array
	$data = array_column( $data , null, $index );

	//Creëer objecten
	$countries = array_map(
		function( $item ) {
			return new Country( $item );
		},
		$data
	);

	//Filter op context TODO: filter op continent
	$countries = array_filter(
		$countries, 
		function( $country ) use ( $context ) {
			return ( 'all' == $context 
				|| ( 'workcamps' == $context && $country->has_workcamps() )
				|| ( 'esc_projects' == $context && $country->has_esc_projects() )
				|| ( 'tailor_made_projects' == $context && $country->has_tailor_made_projects() )
				|| ( 'allowed' == $context && $country->is_allowed() )
				|| ( $context == $country->get_continent()->get_slug() )
			);
		}
	);

	if ( 'array' == $return ) {
		$countries = array_map(
			function( $country ) {
				return $country->get_name();
			},
			$countries
		);
	}
	wp_cache_set( "{$context}_{$index}_{$return}", $countries, 'siw_countries' );

	return $countries;
}

/**
 * Geeft land terug op basis van zoekterm (slug of ISO-code)
 * 
 * @since     3.0.0
 *
 * @param string $country
 * @param string $index
 * @return Country
 */
function siw_get_country( string $country, string $index = 'slug' ) : ?Country {
	$countries = siw_get_countries( 'all', $index );
	return $countries[ $country ] ?? null;
}
