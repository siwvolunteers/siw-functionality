<?php
/**
 * Functies m.b.t. valuta's
 * 
 * @package   SIW\Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
/* Class laden */
require_once( __DIR__ . '/class-siw-currency.php' );

/**
 * Geeft een array met valuta's terug
 *
 * @param string $index
 * @return SIW_Currency[]
 */
function siw_get_currencies() {

	$currencies = wp_cache_get( "currencies", 'siw_currencies' );
	if ( false !== $currencies ) {
		return $currencies;
	}

	$data = require SIW_DATA_DIR . '/currencies.php';
	foreach ( $data as $currency ) {
		$currencies[ $currency['iso'] ] = new SIW_Currency( $currency );
	}
	wp_cache_set( "currencies", $currencies, 'siw_currencies' );

	return $currencies;
}

/**
 * Geeft informatie over een valuta terug
 *
 * @return SIW_Currency
 */
function siw_get_currency( string $currency ) {
	
	$currencies = siw_get_currencies( $currency );

	if ( isset( $currencies[ $currency ] ) ) {
		return $currencies[ $currency ];
	}
	return false;
}
