<?php

namespace SIW\External;

use SIW\Core\HTTP_Request;

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
	public function get_rates() : array {
		$exchange_rates = get_transient( $this->transient_name );
		if ( ! is_array( $exchange_rates ) ) {
			$exchange_rates = $this->retrieve_rates();
			if ( is_null( $exchange_rates ) ) {
				return [];
			}
			set_transient( $this->transient_name, $exchange_rates, DAY_IN_SECONDS );
		}
		return $exchange_rates;
	}

	/**
	 * Geeft wisselkoers voor specifieke valuta terug
	 *
	 * @param string $iso_code
	 *
	 * @return float
	 */
	public function get_rate( string $iso_code ) : ?float {
		$exchange_rates = $this->get_rates();
		return $exchange_rates[ $iso_code ] ?? null;
	}

	/**
	 * Haalt wisselkoeren op bij fixer.io
	 * 
	 * @return array
	 */
	protected function retrieve_rates() : ?array {
		$url = add_query_arg( [
			'access_key' => $this->api_key,
		], self::API_URL );

		$request = new HTTP_Request( $url );
		$response = $request->get();
		
		if ( is_wp_error( $response ) || false == $response['success'] ) {
			return null;
		}
	
		return array_map(
			function( float $rate ) {
				return 1 / $rate;
			},
			$response['rates']
		);
	}
}

