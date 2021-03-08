<?php declare(strict_types=1);

namespace SIW\API;

use SIW\Interfaces\API\Endpoint as Endpoint_Interface;

use SIW\Util;
use SIW\External\Postcode_Lookup as External_Postcode_Lookup;

/**
 * API endpoint voor opzoeken adres obv postcode+huisnummer
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Postcode_Lookup implements Endpoint_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'postcode_lookup';
	}

	/** {@inheritDoc} */
	public function get_methods(): array {
		return [ \WP_REST_Server::READABLE ];
	}

	/** {@inheritDoc} */
	public function get_args(): array {
		return [
			'postcode'    => [
				'required' => true,
				'type'     => 'string',
				'pattern'  => Util::get_pattern( 'postcode' ),
			],
			'housenumber' => [
				'required' => true,
				'type'     => 'string',
				'pattern'  => Util::get_pattern( 'housenumber')
			],
		];
		
	}

	/** {@inheritDoc} */
	public function get_script_data(): array {
		return [
			'deps'       => ['polyfill'],
			'parameters' => [
				'regex' => [
					'postcode'    => Util::get_pattern( 'postcode' ),
					'housenumber' => Util::get_pattern( 'housenumber' )
				]
			],
		];
	}

	/** {@inheritDoc} */
	public function callback( \WP_REST_Request $request): \WP_REST_Response {
		$postcode = $request->get_param( 'postcode' );
		$housenumber = $request->get_param( 'housenumber' );
		
		$postcode_lookup = new External_Postcode_Lookup;
		$address = $postcode_lookup->get_address( $postcode, $housenumber );

		if ( ! is_array( $address ) ) {
			return new \WP_Rest_Response( [
				'data' => null,
			], \WP_Http::NOT_FOUND );
		}

		return new \WP_REST_Response( [
			'data' => $address,
		], \WP_Http::OK ); 
	}
}
