<?php

namespace SIW\API;

/**
 * Abstracte klasse voor API-endpoint
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Endpoint {

	/**
	 * Namespace
	 *
	 * @var string
	 */
	protected $namespace = 'siw';

	/**
	 * Versie
	 *
	 * @var string
	 */
	protected $version = 'v1';

	/**
	 * Resource
	 *
	 * @var string
	 */
	protected $resource;

	/**
	 * Toegestane methodes
	 *
	 * @var array
	 */
	protected $methods;

	/**
	 * Naam van callback-functie
	 *
	 * @var string
	 */
	protected $callback;

	/**
	 * Slug voor script
	 *
	 * @var string
	 */
	protected $script;

	/**
	 * Functie om permissie te controleren
	 *
	 * @var string
	 */
	protected $permission_callback = 'verify_nonce';

	/**
	 * Parameters
	 *
	 * @var array
	 */
	protected $parameters;

	/**
	 * Parameters voor script
	 *
	 * @var array
	 */
	protected $script_parameters;

	/**
	 * Args voor route
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Init
	 */
	public static function init() {
		$self = new static();
		$self->set_parameters();
		$self->set_args();
		add_action( 'rest_api_init', [ $self, 'register_route' ] );
		$self->set_script_parameters();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_script' ] );
	}

	/**
	 * Registreert REST route
	 */
	public function register_route() {
		register_rest_route( "{$this->namespace}/{$this->version}", $this->resource, [
			[
				'methods'             => $this->methods,
				'callback'            => [ $this, $this->callback ],
				'args'                => $this->args,
				'permission_callback' => [ $this, $this->permission_callback ],
			],
		] );
	}

	/**
	 * Set de eigenschappen van de parameters
	 */
	protected function set_args() {
		$parameters = $this->parameters;
		foreach ( $parameters as $parameter => $required ) {
			$args[ $parameter ] = [
				'required'          => $required,
				'validate_callback' => [ $this, "validate_{$parameter}"],
				'sanitize_callback' => [ $this, "sanitize_{$parameter}"],
			];
		}
		$this->args = $args;
	}

	/**
	 * Zet parameters
	 */
	abstract protected function set_parameters();

	/**
	 * Zet extra parameters voor script
	 */
	protected function set_script_parameters() {}

	/**
	 * Valideert nonce
	 *
	 * @param \WP_REST_Request $request
	 * @return bool
	 */
	public function verify_nonce( \WP_REST_Request $request ) {
		$nonce = $request->get_header( 'x_wp_nonce' );
		return wp_verify_nonce( $nonce, 'wp_rest' );
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_script() {
		wp_register_script( "siw-api-{$this->script}", SIW_ASSETS_URL . "js/api/siw-{$this->script}.js", [], SIW_PLUGIN_VERSION, true );
		$script_parameters = wp_parse_args(
			$this->script_parameters,
			[
				'nonce'     => wp_create_nonce( 'wp_rest' ),
				'url'       => get_rest_url( null, "/{$this->namespace}/{$this->version}/{$this->resource}"),
			]
		);
		wp_localize_script( "siw-api-{$this->script}", "siw_api_{$this->script}", $script_parameters );
		wp_enqueue_script( "siw-api-{$this->script}" );
	}
}
