<?php

namespace SIW\External;

/**
 * Ophalen wisselkoersen bij fixer.io
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @link      https://fixer.io/documentation
 */
class Exchange_Rates{

	/**
	 * API url
	 *
	 * @var string
	 */
	const API_URL = 'http://data.fixer.io/api/latest';

	/**
	 * API key
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Transient naam
	 *
	 * @var string
	 */
	protected $transient_name = 'siw_exchange_rates';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_key = siw_get_option( 'exchange_rates_api_key' );
		if ( empty( $this->api_key ) ) {
			return;
		}
	}

	/**
	 * Geeft wisselkoersen terug
	 * 
	 * @return array
	 */
	public function get_rates() {
		$exchange_rates = get_transient( $this->transient_name );

		if ( false === $exchange_rates ) {
			$exchange_rates = $this->retrieve_rates();
			if ( false == $exchange_rates ) {
				return false;
			}
			set_transient( $this->transient_name, $exchange_rates, DAY_IN_SECONDS );
		}
		return $exchange_rates;
	}

	/**
	 * Haalt wisselkoeren op bij fixer.io
	 * 
	 * @return array
	 */
	protected function retrieve_rates() {
		$url = add_query_arg( [
			'access_key' => $this->api_key,
		], self::API_URL );

		$args = [
			'timeout'     => 10,
			'redirection' => 0,
		];

		$response = wp_safe_remote_get( $url, $args );

		if ( false === $this->check_response( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( false == $body['success'] ) {
			return false;
		}
	
		$exchange_rates = [];
		foreach ( $body['rates'] as $currency => $rate ) {
			$exchange_rates[ $currency ] = 1 / $rate;
		}
		return $exchange_rates;
	}

	/**
	 * Controleert response
	 *
	 * @param array|\WP_Error $response
	 * @return bool
	 */
	protected function check_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return false;
		}
	
		$statuscode = wp_remote_retrieve_response_code( $response );
		if ( \WP_Http::OK != $statuscode ) {
			return false;
		}
		return true;
	}

}
