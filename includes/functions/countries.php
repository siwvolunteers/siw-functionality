<?php declare(strict_types=1);

/**
 * Functies m.b.t. landen
 * 
 * @copyright SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Country;

/**
 * Geeft array van gegevens van landen terug
 * @return Country[]
 */
function siw_get_countries( string $context = Country::ALL, string $index = 'slug' ) { 

	$countries = wp_cache_get( "{$context}_{$index}", __FUNCTION__ );
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
		fn( Country $country ) : bool => $country->is_valid_for_context( $context )
	);

	wp_cache_set( "{$context}_{$index}", $countries, __FUNCTION__ );
	return $countries;
}

/** Geeft lijst van landen terug */
function siw_get_countries_list( string $context = Country::ALL, string $index = 'slug' ) : array {
	return array_map(
		fn( Country $country ) : string => $country->get_name(),
		siw_get_countries( $context, $index )
	);
}

/** Geeft land terug op basis van zoekterm */
function siw_get_country( string $country, string $index = 'slug' ) : ?Country {
	$countries = siw_get_countries( Country::ALL, $index );
	return $countries[ $country ] ?? null;
}
