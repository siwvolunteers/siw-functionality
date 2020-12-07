<?php declare(strict_types=1);

namespace SIW\API;

use SIW\Util;
use SIW\External\Postcode_Lookup as External_Postcode_Lookup;

/**
 * API endpoint voor opzoeken adres obv postcode
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Postcode_Lookup extends Endpoint {

	/**
	 * {@inheritDoc}
	 */
	protected string $resource = 'postcode_lookup';

	/**
	 * {@inheritDoc}
	 */
	protected array $methods = [ \WP_REST_Server::READABLE ];

	/**
	 * {@inheritDoc}
	 */
	protected string $callback = 'postcode_lookup';

	/**
	 * {@inheritDoc}
	 */
	protected string $script = 'postcode';
	
	/**
	 * {@inheritDoc}
	 */
	protected array $script_deps = ['polyfill'];

	/**
	 * {@inheritDoc}
	 */
	protected function set_parameters() {
		$this->parameters = [
			'postcode'    => true,
			'housenumber' => true,
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function set_script_parameters() {
		$this->script_parameters['regex'] = Util::get_pattern('postal_code');
	}

	/**
	 * Formatteert postcode
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_postcode( string $param, \WP_REST_Request $request, string $key ) : string {
		return preg_replace( '/[\s\-]/', '', trim( strtoupper( $param ) ) );
	}

	/**
	 * Formatteert huisnummer
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_housenumber( string $param, \WP_REST_Request $request, string $key ) : string {
		return preg_replace("/[^0-9]/", "", $param );
	}

	/**
	 * Valideert postcode
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_postcode( string $param, \WP_REST_Request $request, string $key ) : bool {
		return (bool) preg_match( Util::get_regex('postal_code'), $param );
	}

	/**
	 * Valideert huisnummer
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_housenumber( string $param, \WP_REST_Request $request, string $key ) : bool {
		$housenumber = preg_replace("/[^0-9]/", "", $param );
		return ! empty( $housenumber );
	}

	/**
	 * Handler voor endpoint
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function postcode_lookup( \WP_REST_Request $request ) : \WP_REST_Response {
	
		$postcode = $request->get_param('postcode');
		$housenumber = $request->get_param('housenumber');
		
		$postcode_lookup = new External_Postcode_Lookup;
		$address = $postcode_lookup->get_address( $postcode, $housenumber );

		if ( ! is_array( $address ) ) {
			return new \WP_Rest_Response( [
				'success' => false
			], \WP_Http::OK );
		}

		return new \WP_REST_Response( [
			'success' => true,
			'data'    => $address,
		], \WP_Http::OK ); 
	}
}
