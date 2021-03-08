<?php declare(strict_types=1);

namespace SIW\External;

use SIW\Helpers\HTTP_Request;
use SIW\Util;

/**
 * Opzoeken adres obv postcode en huisnummer
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://github.com/PDOK/locatieserver
 */
class Postcode_Lookup{

	/** Locatieserver URL voor postcode lookup */
	const API_URL = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free';

	/** Zoekt straat en woonplaats op basis van postcode en huisnummer */
	public function get_address( string $postcode, string $housenumber ) : ?array {

		// Check postcode en huisnummer tegen regex
		if ( ! preg_match( Util::get_regex( 'postcode' ), $postcode ) || ! preg_match(  Util::get_regex( 'housenumber' ), $housenumber ) ) {
			return null;
		}

		//Spaties uit postcode verwijderen en omzetten naar hoofdletters
		$postcode = preg_replace( '/[\s\-]/', '', trim( strtoupper( $postcode ) ) );

		//Alleen huisnummer gebruiken, zonder toevoeging
		$housenumber_parts = preg_split("/[^1-9]/", $housenumber); 
		$housenumber = $housenumber_parts[0];
		
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
