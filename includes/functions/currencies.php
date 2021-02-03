<?php declare(strict_types=1);

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
 * @param string $return
 *
 * @return Currency[]|array
 */
function siw_get_currencies( string $return = 'objects' ) : array {

	$currencies = wp_cache_get( "{$return}", 'siw_currencies' );
	if ( false !== $currencies ) {
		return $currencies;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'currencies' );
	$data = wp_list_sort( $data, 'name' );

	//Gebruik iso als index van array
	$data = array_column( $data , null, 'iso_code' );

	//Creëer objecten
	$currencies = array_map(
		fn( array $item ) : Currency => new Currency( $item ),
		$data
	);

	if ( 'array' == $return ) {
		$currencies = array_map(
			fn( Currency $currency ) : string => $currency->get_name(),
			$currencies
		);
	}
	wp_cache_set( "{$return}", $currencies, 'siw_currencies' );

	return $currencies;
}

/**
 * Geeft informatie over een valuta terug
 * 
 * @since  3.0.0
 *
 * @return Currency|null
 */
function siw_get_currency( string $currency ) : ?Currency {
	$currencies = siw_get_currencies();
	return $currencies[ $currency ] ?? null;
}
