<?php declare(strict_types=1);

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

	/** Locatieserver URL voor postcode lookup */
	const API_URL = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free';

	/** Zoekt straat en woonplaats op basis van postcode en huisnummer */
	public function get_address( string $postcode, string $housenumber ) : ?array {
		$address = get_transient( "siw_address_{$postcode}_{$housenumber}" );
		if ( ! is_array( $address ) ) {
			$address = $this->retrieve_address( $postcode, $housenumber );
			if ( ! is_array( $address ) ) {
				return null;
			}
			set_transient( "siw_address_{$postcode}_{$housenumber}", $address, MONTH_IN_SECONDS );
		}
		return $address;
	}

	/** Haalt adres op bij PostcodeAPI.nu */
	protected function retrieve_address( string $postcode, string $housenumber ) : ?array {
		$url = add_query_arg( [
			'q'  => "postcode:{$postcode}",
			'fq' => "huisnummer:{$housenumber}",
		], self::API_URL );

		$request = new HTTP_Request( $url );
		$response = $request->get();

		if ( is_wp_error( $response ) || 0 === $response['response']['numFound'] ) {
			return null;
		}
		return [
			'street' => $response['response']['docs'][0]['straatnaam'],
			'city'   => $response['response']['docs'][0]['woonplaatsnaam'],
		];
	}
}
