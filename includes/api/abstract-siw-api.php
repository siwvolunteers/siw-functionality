<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstracte klasse voor API
 *
 * @package   SIW\API
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
abstract class SIW_API {

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
	 * Valideert nonce
	 *
	 * @param WP_REST_Request $request
	 * @return bool
	 */
	public function verify_nonce( $request ) {
		$nonce = $request->get_header( 'x_wp_nonce' );
		return wp_verify_nonce( $nonce, 'wp_rest' );
	}

	/**
	 * Undocumented function
	 */
	public function enqueue_script() {
		wp_register_script( "siw-{$this->script}", SIW_ASSETS_URL . "js/siw-{$this->script}.js", [ 'jquery' ], SIW_PLUGIN_VERSION, true );
		$parameters = [
			'api_nonce'     => wp_create_nonce( 'wp_rest' ),
			'api_url'       => get_rest_url( null, "/{$this->namespace}/{$this->version}/{$this->resource}"),
		];
		wp_localize_script( "siw-{$this->script}", "siw_{$this->script}", $parameters );
		wp_enqueue_script( "siw-{$this->script}" );
	}
}
