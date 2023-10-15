<?php declare(strict_types=1);

use SIW\Data\Continent;

/**
 * Haal gegevens van continenten op
 *
 * @return Continent[]
 */
function siw_get_continents(): array {

	$continents = wp_cache_get( __FUNCTION__ );
	if ( false !== $continents ) {
		return $continents;
	}
	// Data ophalen TODO: sorteren ?
	$data = siw_get_data( 'continents' );

	// Zet index van array
	$data = array_column( $data, null, 'slug' );

	// Creëer objecten
	$continents = array_map(
		fn( array $item ): Continent => new Continent( $item ),
		$data
	);

	wp_cache_set( __FUNCTION__, $continents );
	return $continents;
}

/** Geeft lijst van continenten terug */
function siw_get_continents_list(): array {
	return array_map(
		fn( Continent $continent ): string => $continent->get_name(),
		siw_get_continents()
	);
}

/** Haal gegevens van continent op (op basis van slug) */
function siw_get_continent( string $slug ): ?Continent {
	$continents = siw_get_continents();
	return $continents[ $slug ] ?? null;
}
