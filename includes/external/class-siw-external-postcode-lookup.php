<?php

/**
 * Opzoeken adres obv postcode en huisnummer
 *
 * @package   SIW\External
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @link      https://www.postcodeapi.nu/docs/
 */
class SIW_External_Postcode_Lookup{

	/**
	 * API key
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_key = siw_get_option( 'postcode_api_key' );
		if ( empty( $this->api_key ) ) {
			return;
		}
	}

	/**
	 * Zoekt straat en woonplaats op basis van postcode en huisnumme
	 *
	 * @param string $postcode
	 * @param string $housenumber
	 * @return array
	 * 
	 * @todo sanitize values
	 */
	public function get_address( string $postcode, string $housenumber ) {
		$address = get_transient( "siw_address_{$postcode}_{$housenumber}" );
		if ( false === $address ) {
			$address = $this->retrieve_address( $postcode, $housenumber );
			if ( false === $address ) {
				return false;
			}
			set_transient( "siw_address_{$postcode}_{$housenumber}", $address, MONTH_IN_SECONDS );
		}
		return $address;
	}

	/**
	 * Haalt adres op bij PostcodeAPI.nu
	 *
	 * @param string $postcode
	 * @param string $housenumber
	 * @return array
	 */
	protected function retrieve_address( string $postcode, string $housenumber ) {
		$url = add_query_arg( [
			'postcode' => $postcode,
			'number'   => $housenumber,
		], SIW_Properties::POSTCODE_API_URL );

		$args = [
			'timeout'     => 10,
			'redirection' => 0,
			'headers'     => [ 'X-Api-Key' => $this->api_key ],
		];

		$response = wp_safe_remote_get( $url, $args );
		if ( is_wp_error( $response ) ) {
			return false;
		}
	
		$statuscode = wp_remote_retrieve_response_code( $response );
		if ( 200 != $statuscode ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $body->_embedded->addresses ) {
			$street = $body->_embedded->addresses[0]->street;
			$city = $body->_embedded->addresses[0]->city->label;
			$address = [
				'street' => $street,
				'city'   => $city,
			];
			return $address;
		}
		return false;
	}
}
