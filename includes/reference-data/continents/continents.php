<?php
/**
 * Functies m.b.t. continenten
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-continent.php' );

/**
 * Haal gegevens van continenten op
 *
 * @return SIW_Continent[]
 */
function siw_get_continents() { 

	$data = require SIW_DATA_DIR . '/continents.php';

	$continents = [];
	foreach ( $data as $continent ) {
		$continents[ $continent['slug'] ] = new SIW_Continent( $continent );
	}

	return $continents;
}


/**
 * Haal gegevens van continent op (op basis van slug)
 *
 * @param string $slug
 * @return SIW_Continent
 */
function siw_get_continent( $slug ) {
	$continents = siw_get_continents();
	$continent = isset( $continents[ $slug ] ) ? $continents[ $slug ] : false;

	return $continent;
}