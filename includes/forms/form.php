<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Util\Meta_Box;

/**
 * Class om een formulier via MetaBox te genereren
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Form {

	/** API versie */
	const API_VERSION = 'v1';

	/** Constructor */
	public function __construct( protected Form_Interface $form ) {}

	/** Registreer formulier */
	public function register() {
		add_filter( 'siw_forms', [ $this, 'register_form' ] );
		add_filter( 'siw_rest_urls', [ $this, 'register_rest_urls' ] );
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'rest_api_init', [ $this, 'register_route' ] );
	}

	/** Registreer formulier TODO: registry ipv filter*/
	public function register_form( array $forms ): array {
		$forms[ $this->form->get_form_id() ] = $this->form->get_form_name();
		return $forms;
	}

	/** Registreert REST route */
	public function register_route() {
		register_rest_route(
			$this->get_namespace(),
			$this->get_route(),
			[
				[
					'methods'             => [ \WP_REST_Server::CREATABLE ],
					'callback'            => [ $this, 'callback' ],
					'args'                => $this->get_args(),
					'permission_callback' => [ $this, 'verify_nonce' ],
				],
			]
		);
	}

	/** Geeft namespace terug */
	protected function get_namespace(): string {
		return 'siw/' . self::API_VERSION;
	}

	/** Geeft route terug */
	public function get_route(): string {
		$id = str_replace( '_', '-', $this->form->get_form_id() );
		return "form/{$id}";
	}

	/** Valideert nonce */
	public function verify_nonce( \WP_REST_Request $request ): bool {
		$nonce = $request->get_param( "nonce_siw_form_{$this->form->get_form_id()}" );
		return boolval( wp_verify_nonce( $nonce, "rwmb-save-siw_form_{$this->form->get_form_id()}" ) );
	}

	/** Callback voor REST API */
	public function callback( \WP_REST_Request $request ): \WP_REST_Response {
		$processor = new Processor( $this->form, $request );
		return $processor->process();
	}

	/** Geeft REST API args terug TODO: add nonce en _wp_http_referer? */
	protected function get_args(): array {
		$args = array_map(
			[ Meta_Box::class, 'convert_field_to_rest_api_arg' ],
			array_column( $this->form->get_form_fields(), null, 'id' )
		);

		// Lege waardes verwijderen
		return array_filter( $args );
	}

	/** Voegt metabox toe */
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'          => "siw_form_{$this->form->get_form_id()}",
			'title'       => "{$this->form->get_form_name()}",
			'post_types'  => [],
			'toggle_type' => 'slide',
			'fields'      => $this->get_fields(),
		];

		return $meta_boxes;
	}

	/** Haalt formuliervelden op */
	protected function get_fields(): array {
		$fields = $this->form->get_form_fields();
		$fields = array_map( [ $this, 'parse_field' ], $fields );

		$fields = $this->add_quiz( $fields );

		// Voeg verzenden knop toe
		$fields[] = [
			'type'       => 'button',
			'columns'    => Form_Interface::FULL_WIDTH,
			'std'        => __( 'Verzenden', 'siw' ),
			'attributes' => [
				'type' => 'submit',
				'name' => 'rwmb_submit',
			],
		];

		return $fields;
	}

	/** Voeg quiz toe om bots af te schrikken */
	protected function add_quiz( array $fields ): array {

		$one = wp_rand( 2, 10 );
		$two = wp_rand( 2, 10 );

		if ( $one > $two ) {
			$operator = __( 'min', 'siw' );
			$answer = $one - $two;
		} else {
			$operator = __( 'plus', 'siw' );
			$answer = $one + $two;
		}

		$fields[] = [
			'id'       => 'quiz',
			'type'     => 'number',
			'required' => true,
			/* translators: %1$d en %3$d twee zijn allebei getallen, %2$s is de operator (plus of min) */
			'name'     => sprintf( __( 'Hoeveel is %1$d %2$s %3$d?', 'siw' ), $one, $operator, $two ),
			'columns'  => Form_Interface::HALF_WIDTH,
		];
		$fields[] = [
			'id'   => 'quiz_hash',
			'type' => 'hidden',
			'std'  => siw_hash( (string) $answer ),
		];

		return $fields;
	}

	/** Parset veld */
	protected function parse_field( array $field ): array {
		$defaults = [
			'required' => true,
			'columns'  => Form_Interface::HALF_WIDTH,
		];
		return wp_parse_args_recursive( $field, $defaults );
	}
}
