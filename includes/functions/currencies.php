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
 * @return Currency[]
 */
function siw_get_currencies(): array {

	$currencies = wp_cache_get( __FUNCTION__ );
	if ( false !== $currencies ) {
		return $currencies;
	}

	// Data ophalen en sorteren
	$data = siw_get_data( 'currencies' );
	$data = wp_list_sort( $data, 'name' );

	// Gebruik iso als index van array
	$data = array_column( $data, null, 'iso_code' );

	// Creëer objecten
	$currencies = array_map(
		fn( array $item ): Currency => new Currency( $item ),
		$data
	);

	wp_cache_set( __FUNCTION__, $currencies );
	return $currencies;
}

/** Geeft lijst van valuta's terug */
function siw_get_currencies_list(): array {
	return array_map(
		fn( Currency $currency ): string => $currency->get_name(),
		siw_get_currencies()
	);
}

/** Geeft informatie over een valuta terug */
function siw_get_currency( string $currency ): ?Currency {
	$currencies = siw_get_currencies();
	return $currencies[ $currency ] ?? null;
}
