<?php declare(strict_types=1);

/**
 * Functies m.b.t. continenten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Continent;

/**
 * Haal gegevens van continenten op
 * 
 * @param string $return objects|array
 * 
 * @return Continent[]|array
 * 
 * @since     3.0.0
 */
function siw_get_continents( string $return = 'objects' ) : array { 

	$continents = wp_cache_get( "continents_{$return}", 'siw_continents' );
	if ( false !== $continents ) {
		return $continents;
	}
	//Data ophalen TODO: sorteren ?
	$data = siw_get_data( 'continents' );

	//Zet index van array
	$data = array_column( $data , null, 'slug' );

	//Creëer objecten
	$continents = array_map(
		fn( array $item ) : Continent => new Continent( $item ),
		$data
	);

	if ( 'array' == $return ) {
		$continents = array_map(
			fn( Continent $continent ) : string => $continent->get_name(),
			$continents
		);
	}
	wp_cache_set( "continents_{$return}", $continents, 'siw_continents' );
	return $continents;
}

/** Geeft lijst van continenten terug */
function siw_get_continents_list() : array {
	return array_map(
		fn( Continent $continent ) : string => $continent->get_name(),
		siw_get_continents()
	);
}

/**
 * Haal gegevens van continent op (op basis van slug)
 * 
 * @since     3.0.0
 *
 * @param string $slug
 * @return Continent
 */
function siw_get_continent( string $slug ) : ?Continent {
	$continents = siw_get_continents();
	return $continents[ $slug ] ?? null;
}
