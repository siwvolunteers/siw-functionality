<?php
/**
 * Functies m.b.t. continenten
 * 
 * @author      Maarten Bruna
 * @package 	SIW\Reference data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-continent.php' );
require_once( __DIR__ . '/data.php' );

/**
 * Haal gegevens van continenten op
 *
 * @return \SIW_Continent[]
 */
function get_continents() { 
	$data = apply_filters( 'siw_continent_data', [] );
    $continents = [];
    foreach ( $data as $continent ) {
        $continents[ $continent['slug'] ] = new \SIW_Continent( $continent );
    }

	return $continents;
}


/**
 * Haal gegevens van continent op (op basis van slug)
 *
 * @param string $slug
 * @return \SIW_Continent
 */
function get_continent( $slug ) {
    $continents = get_continents();
    $continent = isset( $continents[ $slug ] ) ? $continents[ $slug ] : false;

    return $continent;
}

