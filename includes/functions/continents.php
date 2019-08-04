<?php
/**
 * Functies m.b.t. continenten
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Haal gegevens van continenten op
 *
 * @return SIW_Data_Continent[]
 */
function siw_get_continents() { 

	$continents = wp_cache_get( "continents", 'siw_continents' );
	if ( false !== $continents ) {
		return $continents;
	}

	$data = siw_get_data( 'continents' );

	$continents = [];
	foreach ( $data as $continent ) {
		$continents[ $continent['slug'] ] = new SIW_Data_Continent( $continent );
	}

	wp_cache_set( "continents", $continents, 'siw_continents' );

	return $continents;
}

/**
 * Haal gegevens van continent op (op basis van slug)
 *
 * @param string $slug
 * @return SIW_Data_Continent
 */
function siw_get_continent( string $slug ) {
	$continents = siw_get_continents();
	$continent = isset( $continents[ $slug ] ) ? $continents[ $slug ] : false;

	return $continent;
}