<?php declare(strict_types=1);

namespace SIW\API;

use SIW\Interfaces\API\Endpoint as Endpoint_Interface;

/**
 * Klasse voor het registreren van een API Endpoint
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Endpoint {

	/** Namespace */
	protected string $namespace = 'siw';

	/** Versie */
	protected string $version = 'v1';

	/** Init */
	public function __construct( protected Endpoint_Interface $endpoint ) {
		add_action( 'rest_api_init', [ $this, 'register_route' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	/** Registreert REST route */
	public function register_route() {
		register_rest_route( "{$this->namespace}/{$this->version}", $this->get_handle_id(), [
			[
				'methods'             => $this->endpoint->get_methods(),
				'callback'            => [ $this->endpoint, 'callback' ],
				'args'                => $this->endpoint->get_args(),
				'permission_callback' => [ $this, 'verify_nonce' ],
			],
		] );
	}

	/** Valideert nonce */
	public function verify_nonce( \WP_REST_Request $request ): bool {
		$nonce = $request->get_header( 'x_wp_nonce' );
		return boolval( wp_verify_nonce( $nonce, 'wp_rest' ) );
	}

	/** Voegt scripts toe */
	public function enqueue_script() {
		$script_data = wp_parse_args_recursive(
			$this->endpoint->get_script_data(),
			[
				'src'        => SIW_ASSETS_URL . "js/api/siw-{$this->get_handle_id()}.js",
				'deps'       => [],
				'version'    => SIW_PLUGIN_VERSION,
				'parameters' => [],
			]
		);

		wp_register_script( "siw-api-{$this->get_handle_id()}", $script_data['src'], $script_data['deps'], $script_data['version'], true );
		$script_parameters = wp_parse_args(
			$script_data['parameters'],
			[
				'nonce'     => wp_create_nonce( 'wp_rest' ),
				'url'       => get_rest_url( null, "/{$this->namespace}/{$this->version}/{$this->get_handle_id()}"),
			]
		);
		wp_localize_script( "siw-api-{$this->get_handle_id()}", "siw_api_{$this->get_object_id()}", $script_parameters );
		wp_enqueue_script( "siw-api-{$this->get_handle_id()}" );
	}

	/** Geeft id voor gebruik als handlet terug */
	protected function get_handle_id() : string {
		return str_replace( '_', '-', $this->endpoint->get_id() );
	}

	/** Geeft ID voor gebruik in javascript object terug */
	protected function get_object_id() : string {
		return str_replace( '-', '_', $this->endpoint->get_id() );
	}
}
