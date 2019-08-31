<?php

/**
 * Opzoeken adres obv postcode en huisnummer
 *
 * @package   SIW\External
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @link      https://github.com/PDOK/locatieserver
 */
class SIW_External_Postcode_Lookup{

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
			'q'  => "postcode:{$postcode}",
			'fq' => "huisnummer:{$housenumber}",
		], SIW_Properties::POSTCODE_API_URL );

		$args = [
			'timeout'     => 10,
			'redirection' => 0,
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

		if ( 0 === $body->response->numFound ) {
			return false;
		}

		$address = [
			'street' => $body->response->docs[0]->straatnaam,
			'city'   => $body->response->docs[0]->woonplaatsnaam,
		];
		return $address;
	}
}
