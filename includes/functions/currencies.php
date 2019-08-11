<?php
/**
 * Functies m.b.t. valuta's
 * 
 * @package   SIW\Functions
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

/**
 * Geeft een array met valuta's terug
 *
 * @param string $index
 * @return SIW_Data_Currency[]
 */
function siw_get_currencies() {

	$currencies = wp_cache_get( "currencies", 'siw_currencies' );
	if ( false !== $currencies ) {
		return $currencies;
	}

	$data = siw_get_data( 'currencies' );
	foreach ( $data as $currency ) {
		$currencies[ $currency['iso'] ] = new SIW_Data_Currency( $currency );
	}
	wp_cache_set( "currencies", $currencies, 'siw_currencies' );

	return $currencies;
}

/**
 * Geeft informatie over een valuta terug
 *
 * @return SIW_Data_Currency
 */
function siw_get_currency( string $currency ) {
	
	$currencies = siw_get_currencies();

	if ( isset( $currencies[ $currency ] ) ) {
		return $currencies[ $currency ];
	}
	return false;
}