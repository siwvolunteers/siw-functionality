<?php declare(strict_types=1);

namespace SIW\Helpers;

class HTTP_Request {

	public const APPLICATION_JSON = 'application/json';
	public const APPLICATION_XML = 'application/xml';
	public const APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

	/** Geaccepteerde response codes */
	private const SUCCESS_RESPONSE_CODES = [
		\WP_Http::OK,
		\WP_Http::CREATED,
		\WP_Http::NO_CONTENT,
	];

	protected string $url;
	protected array $args;
	protected \SimpleXMLElement|array $body;
	protected \WP_Error $error;


	protected function __construct() {}

	public static function create( string $url, array $args = [] ): self {
		$self = new self();
		$self->url = $url;
		$self->args = \wp_parse_args_recursive(
			$args,
			[
				'timeout'     => 60,
				'redirection' => 0,
				'headers'     => [
					'accept'       => self::APPLICATION_JSON, // TODO: is een default eigenlijk wel handig?
					'content-type' => self::APPLICATION_JSON,
				],
				'body'        => [],
			]
		);
		return $self;
	}

	public function set_basic_auth( string $user, string $password ): self {
		$this->args['headers']['Authorization'] = 'Basic ' . base64_encode( "{$user}:{$password}" ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return $this;
	}

	public function set_bearer_auth( string $token ): self {
		$this->args['headers']['Authorization'] = "Bearer {$token}";
		return $this;
	}

	public function set_content_type( string $content_type ): self {
		$this->args['headers']['content-type'] = $content_type;
		return $this;
	}

	public function add_query_arg( string $key, string $value ): self {
		$this->url = add_query_arg( $key, $value, $this->url );
		return $this;
	}

	public function add_query_args( array $query_args ): self {
		$this->url = add_query_arg( $query_args, $this->url );
		return $this;
	}

	public function set_accept( string $accept ): self {
		$this->args['headers']['accept'] = $accept;
		return $this;
	}

	public function post( $body = [] ): \SimpleXMLElement|array|\WP_Error {
		$this->args['body'] = $body;
		return $this->dispatch( \WpOrg\Requests\Requests::POST );
	}

	public function get(): \SimpleXMLElement|array|\WP_Error {
		return $this->dispatch( \WpOrg\Requests\Requests::GET );
	}

	public function patch( $body = [] ): \SimpleXMLElement|array|\WP_Error {
		$this->args['body'] = $body;
		return $this->dispatch( \WpOrg\Requests\Requests::PATCH );
	}

	public function put( $body = [] ): \SimpleXMLElement|array|\WP_Error {
		$this->args['body'] = $body;
		return $this->dispatch( \WpOrg\Requests\Requests::PUT );
	}

	protected function dispatch( string $method ): \SimpleXMLElement|array|\WP_Error {
		$this->args['method'] = $method;
		if ( self::APPLICATION_JSON === $this->args['headers']['content-type'] && ! in_array( $method, [ \WpOrg\Requests\Requests::GET, \WpOrg\Requests\Requests::HEAD ], true ) ) {
			$this->args['body'] = wp_json_encode( $this->args['body'] );
		}

		$response = \wp_safe_remote_request( $this->url, $this->args );
		if ( $this->is_valid_response( $response ) && $this->has_valid_body( $response ) ) {
			return $this->body;
		} else {
			return $this->error;
		}
	}

	protected function has_valid_body( array|\WP_Error $response ): bool {
		$body = \wp_remote_retrieve_body( $response );
		switch ( $this->args['headers']['accept'] ) {
			case self::APPLICATION_JSON:
				$json = \json_decode( $body, true );
				if ( null === $json ) {
					$this->error = new \WP_Error( 'invalid_json', json_last_error_msg() );
					return false;
				}
				$this->body = $json;
				break;
			case self::APPLICATION_XML:
				libxml_use_internal_errors( true );
				$xml = \simplexml_load_string( $body );
				if ( false === $xml ) {
					$this->error = new \WP_Error( 'invalid_xml', libxml_get_last_error()->message );
					libxml_use_internal_errors( false );
					return false;
				}
				libxml_use_internal_errors( false );
				$this->body = $xml;
				break;
			default:
				$this->body = $body;
		}
		return true;
	}

	protected function is_valid_response( array|\WP_Error $response ): bool {
		if ( is_wp_error( $response ) ) {
			$this->error = $response;
			return false;
		}
		$statuscode = wp_remote_retrieve_response_code( $response );
		if ( ! in_array( $statuscode, self::SUCCESS_RESPONSE_CODES, true ) ) {
			$this->error = new \WP_Error( 'invalid_status', 'Reponse heeft geen geldige response code' );
			return false;
		}
		return true;
	}
}
