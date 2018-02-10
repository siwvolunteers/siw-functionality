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
	$month = date ( 'Ym' );
	$exchange_rates = get_transient( "siw_exchange_rates_{$month}" );

	if ( false === $exchange_rates ) {
		$exchange_rates = siw_get_exchange_rates( $month );
		if ( false == $exchange_rates ) {
			return false;
		}
		set_transient( "siw_exchange_rates_{$month}", $exchange_rates, MONTH_IN_SECONDS );
	}

	$exchange_rate = isset( $exchange_rates[ $currency ] ) ? $exchange_rates[ $currency ] : false;

	return $exchange_rate;
}


/**
 * Ophalen wisselkoersen bij belastingdienst voor een specifieke maand
 * @param  string $month
 * @return array
 */
function siw_get_exchange_rates( $month ) {
	$url = "https://www.belastingdienst.nl/data/douane_wisselkoersen/wks.douane.wisselkoersen.dd{$month}.xml";
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
	);
	$response = wp_safe_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		return false; //TODO: foutafhandeling
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		return false; //TODO: foutafhandeling
	}
	$body = simplexml_load_string( wp_remote_retrieve_body( $response ) );

	$exchange_rates = array();
	foreach ( $body->children() as $exchange_rate ) {
		if ( isset( $exchange_rate->muntCode ) ) {
			$currency = (string) $exchange_rate->muntCode;
			$rate = (string) $exchange_rate->tariefInEuro;
			$rate = floatval( str_replace(",", ".", $rate ) );
			$exchange_rates[$currency] = $rate;
		}
	}
	return $exchange_rates;
}
