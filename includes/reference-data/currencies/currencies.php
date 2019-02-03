<?php
/**
 * Functies m.b.t. valuta's
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Class laden */
require_once( __DIR__ . '/class-siw-currency.php' );
require_once( __DIR__ . '/data.php' );

/**
 * Geeft een array met valuta's terug
 *
 * @param string $index
 * @return SIW_Currency[]
 */
function siw_get_currencies() {

	$data = [];
	/**
	 * Array met gegevens van de valuta
	 *
	 * @param array $data Gegevens van de valuta {iso|symbol|name}
	 */
	$data = apply_filters( 'siw_currency_data', $data );
	
	foreach ( $data as $currency ) {
		$currencies[ $currency['iso'] ] = new SIW_Currency( $currency );
	}

	return $currencies;
}


/**
 * Geeft informatie over een valuta terug
 *
 * @return SIW_Currency
 */
function siw_get_currency( $currency ) {
	
	$currencies = siw_get_currencies( $currency );

	if ( isset( $currencies[ $currency ] ) ) {
		return $currencies[ $currency ];
	}
	return false;
}