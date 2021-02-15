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

	//Data van verschillende continenten combineren
	$continents = siw_get_continents();
	foreach ( $continents as $continent ) {
		$data = array_merge(
			$data ?? [],
			array_map(
				fn( $country ) => array_merge( $country, [ 'continent' => $continent->get_slug()]),
				siw_get_data( "countries/{$continent->get_slug()}" )
			)
		);
	}

	//Sorteren op naam
	$data = wp_list_sort( $data, 'name' );

	//Zet index van array
	$data = array_column( $data , null, $index );

	//Creëer objecten
	$countries = array_map(
		fn( array $item ) : Country => new Country( $item ),
		$data
	);

	//Filter op context
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
			fn( Country $country ) : string => $country->get_name(),
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
 * @return Country|null
 */
function siw_get_country( string $country, string $index = 'slug' ) : ?Country {
	$countries = siw_get_countries( 'all', $index );
	return $countries[ $country ] ?? null;
}
