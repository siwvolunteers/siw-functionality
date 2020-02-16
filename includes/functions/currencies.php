<?php

/**
 * Functies m.b.t. valuta's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Currency;

/**
 * Geeft een array met valuta's terug
 * 
 * @since     3.0.0
 *
 * @param string $index
 * @return Currency[]
 */
function siw_get_currencies() {

	$currencies = wp_cache_get( "currencies", 'siw_currencies' );
	if ( false !== $currencies ) {
		return $currencies;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'currencies' );
	$data = wp_list_sort( $data, 'name' );

	//Gebruik iso als index van array
	$data = array_column( $data , null, 'iso' );

	//Creëer objecten
	$currencies = array_map(
		function( $item ) {
			return new Currency( $item );
		},
		$data
	);
	wp_cache_set( "currencies", $currencies, 'siw_currencies' );

	return $currencies;
}

/**
 * Geeft informatie over een valuta terug
 * 
 * @since     3.0.0
 *
 * @return Currency
 */
function siw_get_currency( string $currency ) {
	$currencies = siw_get_currencies();
	return $currencies[ $currency ] ?? false;
}
