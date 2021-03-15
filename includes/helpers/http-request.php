<?php declare(strict_types=1);

namespace SIW\Helpers;

/**
 * Class om een HTTP request uit te voeren
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class HTTP_Request {

	/** JSON */
	const APPLICATION_JSON = 'application/json';

	/** XML */
	const APPLICATION_XML = 'application/xml';

	/** Form */
	const APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

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
	protected function __construct() {}

	/** Maak request aan */
	public static function create( string $url, array $args = [] ) : self {
		$self = new self();
		$self->url = $url;
		$self->args = \wp_parse_args_recursive(
			$args,
			[
				'timeout'     => 60,
				'redirection' => 0,
				'headers'     => [ 
					'accept'       => self::APPLICATION_JSON, //TODO: is een default eigenlijk wel handig?
					'content-type' => self::APPLICATION_JSON,
				],
				'body'        => [],
			]
		);
		return $self;
	}

	/** Zet basic auth header */
	public function set_basic_auth( string $user, string $password ) : self {
		$this->args['headers']['Authorization'] = 'Basic ' . base64_encode("{$user}:{$password}");
		return $this;
	}

	/** Zet bearer auth header */
	public function set_bearer_auth( string $token ) :self {
		$this->args['headers']['Authorization'] = "Bearer {$token}";
		return $this;
	}

	/** Voegt toegestane response code toe */
	public function add_accepted_response_code( int $response_code ) : self {
		$this->accepted_response_codes[] = $response_code;
		return $this;
	}

	/** Zet content type van request */
	public function set_content_type( string $content_type ) : self {
		$this->args['headers']['content-type'] = $content_type;
		return $this;
	}

	/** Zet geaccepteerde formaat van response */
	public function set_accept( string $accept ) : self {
		$this->args['headers']['accept'] = $accept;
		return $this;
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
			case self::APPLICATION_JSON:
				$body = \json_decode( $body, true );
				break;
			case self::APPLICATION_XML:
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
			$this->error = new \WP_Error( 'invalid_status', 'Reponse heeft geen geldige response code' );
			return false;
		}
		return true;
	}
}
