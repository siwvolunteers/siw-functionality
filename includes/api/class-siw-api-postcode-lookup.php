<?php

/**
 * API endpoint voor opzoeken adres obv postcode
 *
 * @package   SIW\API
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_External_Postcode_Lookup
 * @uses      SIW_Util
 */
class SIW_API_Postcode_Lookup extends SIW_API {

	/**
	 * {@inheritDoc}
	 */
	protected $resource = 'postcode_lookup';

	/**
	 * {@inheritDoc}
	 */
	protected $methods = WP_REST_Server::READABLE;

	/**
	 * {@inheritDoc}
	 */
	protected $callback = 'postcode_lookup';

	/**
	 * {@inheritDoc}
	 */
	protected $script = 'postcode';

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
	 * Formatteert postcode
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_postcode( string $param, WP_REST_Request $request, string $key ) {
		return preg_replace( '/[\s\-]/', '', trim( strtoupper( $param ) ) );
	}

	/**
	 * Formatteert huisnummer
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_housenumber( string $param, WP_REST_Request $request, string $key ) {
		return preg_replace("/[^0-9]/", "", $param );
	}

	/**
	 * Valideert postcode
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_postcode( string $param, WP_REST_Request $request, string $key ) {
		return (bool) preg_match( SIW_Util::get_regex('postcode'), $param );
	}

	/**
	 * Valideert huisnummer
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_housenumber( string $param, WP_REST_Request $request, string $key ) {
		$housenumber = preg_replace("/[^0-9]/", "", $param );
		return ! empty( $housenumber );
	}

	/**
	 * Handler voor endpoint
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function postcode_lookup( WP_REST_Request $request ) {
	
		$postcode = $request->get_param('postcode');
		$housenumber = $request->get_param('housenumber');
		
		$postcode_lookup = new SIW_External_Postcode_Lookup;
		$address = $postcode_lookup->get_address( $postcode, $housenumber );

		if ( false === $address ) {
			return new WP_Rest_Response( [
				'success' => false
			], 200 );
		}

		return new WP_REST_Response( [
			'success' => true,
			'data'    => $address,
			], 200
		); 
	}

}
