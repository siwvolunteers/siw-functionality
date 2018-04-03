<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Reken bedrag in vreemde valuta om naar Euro
 * @param  string $currency
 * @param  float $amount
 * @param  int $decimals
 * @return float $amount_in_euro
 */
function siw_get_amount_in_euro( $currency, $amount, $decimals = 0 ) {

	$exchange_rate = siw_get_exchange_rate( $currency );
	if ( false == $exchange_rate ) {
		return false;
	}

	$amount_in_euro = (float) $amount * (float) $exchange_rate;
	$amount_in_euro = number_format( $amount_in_euro, $decimals, ',', '.' );
	return $amount_in_euro;
}


/**
 * Haal wisselkoers naar Euro op voor een specifieke valuta
 * @param  string $currency
 * @return float $exchange_rate
 */
function siw_get_exchange_rate( $currency ) {
	$exchange_rates = get_transient( "siw_exchange_rates" );

	if ( false === $exchange_rates ) {
		$exchange_rates = siw_get_exchange_rates();
		if ( false == $exchange_rates ) {
			return false;
		}
		set_transient( "siw_exchange_rates", $exchange_rates, DAY_IN_SECONDS );
	}

	$exchange_rate = isset( $exchange_rates[ $currency ] ) ? $exchange_rates[ $currency ] : false;

	return $exchange_rate;
}


/**
 * Ophalen wisselkoersen bij fixer.io
 * @return array
 */
function siw_get_exchange_rates() {

	$api_key = siw_get_setting( 'exchange_rates_api_key' );

	if ( empty( $api_key ) ) {
		return false; //TODO: foutafhandeling
	}

	$url = SIW_EXCHANGE_RATES_API_URL . 'latest';
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
	);
	$url = add_query_arg( 'access_key', $api_key, $url );
	$response = wp_safe_remote_get( $url, $args );


	if ( is_wp_error( $response ) ) {
		return false; //TODO: foutafhandeling
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		return false; //TODO: foutafhandeling
	}
	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( false == $body['success'] ) {
		return false; //TODO: foutafhandeling		
	}

	$exchange_rates = array();
	foreach ( $body['rates'] as $currency => $rate ) {
		$exchange_rates[$currency] = 1 / $rate;
	}
	return $exchange_rates;
}
