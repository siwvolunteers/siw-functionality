<?php declare(strict_types=1);

namespace SIW\Core;

use WP_Error;

/**
 * Class om een HTTP request uit te voeren
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class HTTP_Request {

	/** Toegestane methodes TODO: hoe nuttig is dit */
	protected array $allowed_methods = [
		'POST',
		'GET',
		'PATCH'
	];

	/** Geaccepteerde response codes */
	protected array $accepted_response_codes = [
		\WP_Http::OK
	];

	/** Url voor request */
	protected string $url;

	/** Args voor request */
	protected array $args;

	/** Fout bij afhandeling van het request */
	protected \WP_Error $error;

	/** Init */
	public function __construct( string $url, array $args = [] ) {
		$this->url = $url;
		$this->args = \wp_parse_args_recursive(
			$args,
			[
				'timeout'     => 60,
				'redirection' => 0,
				'headers'     => [ 
					'accept'       => 'application/json',
					'content-type' => 'application/json'
				],
				'body'        => [],
			]
		);
	}

	/** Zet basic auth header */
	public function set_basic_auth( string $user, string $password ) {
		$this->args['headers']['Authorization'] = 'Basic ' . base64_encode("{$user}:{$password}");
	}

	/** Zet bearer auth header */
	public function set_bearer_auth( string $token ) {
		$this->args['headers']['Authorization'] = "Bearer {$token}";
	}

	/** Voegt toegestane response code toe */
	public function add_accepted_response_code( int $response_code ) {
		$this->accepted_response_codes[] = $response_code;
	}

	/** Zet content type van request */
	public function set_content_type( string $content_type ) {
		$this->args['headers']['content-type'] = $content_type;
	}

	/** Zet geaccepteerde formaat van response */
	public function set_accept( string $accept ) {
		$this->args['headers']['accept'] = $accept;
	}
	
	/** Voor POST-request uit */
	public function post( $body = [] ) {
		$this->args['body'] = $body;
		return $this->dispatch( 'POST' );
	}

	/** Voert GET-request uit */
	public function get() {
		return $this->dispatch( 'GET' );
	}

	/** Voert PATCH-request uit */
	public function patch() {
		return $this->dispatch( 'PATCH' );
	}

	/** Verstuur request */
	protected function dispatch( string $method ) {
		if ( ! in_array( $method, $this->allowed_methods ) ) {
			return new \WP_Error( 'invalid_method', 'Method is niet toegestaan');
		}
		$this->args['method'] = $method;
		$response = \wp_safe_remote_request( $this->url, $this->args );
		if ( $this->is_valid_response( $response ) ) {
			return $this->retrieve_body( $response );
		}
		else {
			return $this->error;
		}
	}

	/** Haal body van response op */
	protected function retrieve_body( array $response ) {
		$body = \wp_remote_retrieve_body( $response );
		switch ( $this->args['headers']['accept'] ) {
			case 'application/json':
				$body = \json_decode( $body, true );
				break;
			case 'application/xml':
				$body = \simplexml_load_string( $body );
				break;
			default:
				$body = $body;
		}
		return $body;
	}

	/**
	 * Controleert response
	 *
	 * @param array|\WP_Error $response
	 */
	protected function is_valid_response( $response ) : bool {
		if ( is_wp_error( $response ) ) {
			$this->error = $response;
			return false;
		}
		$statuscode = wp_remote_retrieve_response_code( $response );
		if ( ! in_array( $statuscode, $this->accepted_response_codes ) ) {
			$this->error = new WP_Error( 'invalid_status', 'Reponse heeft geen geldige response code' );
			return false;
		}
		return true;
	}
}
