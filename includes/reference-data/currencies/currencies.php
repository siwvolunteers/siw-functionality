<?php
/**
 * Functies m.b.t. valuta's
 * 
 * @package 	SIW\Reference data
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
	 * @param array $data Gegevens van de valuta { iso|symbol|name }
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


/**
 * Geeft array met wisselkoersen terug
 *
 * @todo aparte klasse voor wisselkoersen
 * @return array
 */
function siw_get_exchange_rates() {
	$exchange_rates = get_transient( "siw_exchange_rates" );

	if ( false === $exchange_rates ) {
		$exchange_rates = siw_retrieve_exchange_rates();
		if ( false == $exchange_rates ) {
			return false;
		}
		set_transient( "siw_exchange_rates", $exchange_rates, DAY_IN_SECONDS );
	}

	return $exchange_rates;
}


/**
 * Ophalen wisselkoersen bij fixer.io
 * @todo foutafhandeling
 * @return array
 */
function siw_retrieve_exchange_rates( $force = true ) {

	$api_key = siw_get_setting( 'exchange_rates_api_key' );

	if ( empty( $api_key ) ) {
		return false;
	}

	$url = SIW_EXCHANGE_RATES_API_URL . 'latest';
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
	);
	$url = add_query_arg( 'access_key', $api_key, $url );
	$response = wp_safe_remote_get( $url, $args );


	if ( is_wp_error( $response ) ) {
		return false;
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		return false;
	}
	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( false == $body['success'] ) {
		return false;	
	}

	$exchange_rates = array();
	foreach ( $body['rates'] as $currency => $rate ) {
		$exchange_rates[$currency] = 1 / $rate;
	}
	return $exchange_rates;
}
