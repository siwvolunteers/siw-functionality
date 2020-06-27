<?php

namespace SIW\External;

use SIW\Core\HTTP_Request;

/**
 * Opzoeken adres obv postcode en huisnummer
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @link      https://github.com/PDOK/locatieserver
 */
class Postcode_Lookup{

	/**
	 * Locatieserver URL voor postcode lookup
	 *
	 * @var string
	 */
	const API_URL = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free';

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
		], self::API_URL );

		$request = new HTTP_Request( $url );
		$response = $request->get();

		if ( is_wp_error( $response ) || 0 === $response['response']['numFound'] ) {
			return false;
		}
		return [
			'street' => $response['response']['docs'][0]['straatnaam'],
			'city'   => $response['response']['docs'][0]['woonplaatsnaam'],
		];
	}
}
