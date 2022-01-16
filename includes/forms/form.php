<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Util\Meta_Box;

/**
 * Class om een formulier via MetaBox te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Form {

	/** API versie */
	const API_VERSION = 'v1';

	/** Formulier */
	protected Form_Interface $form;
	
	/** Constructor TODO: php8 constructor property promotion */
	public function __construct( Form_Interface $form ) {
		$this->form = $form;
	}

	/** Registreer formulier */
	public function register() {
		add_filter( 'siw_forms', [ $this, 'register_form'] );
		add_filter( 'siw_rest_urls', [ $this, 'register_rest_urls'] );
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'rest_api_init', [ $this, 'register_route' ] );
	}

	/** Registreer formulier TODO: registry ipv filter*/
	public function register_form( array $forms ): array {
		$forms[$this->form->get_form_id()] = $this->form->get_form_name();
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
			[ Meta_Box::class, 'convert_field_to_rest_api_arg'],
			array_column( $this->form->get_form_fields(), null, 'id' )
		);

		//Lege waardes verwijderen
		return array_filter( $args );
	}

	/** Voegt metabox toe */
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'          => "siw_form_{$this->form->get_form_id()}",
			'title'       => "{$this->form->get_form_name()}",
			'object_type' => 'form',
			'toggle_type' => 'slide',
			'fields'      => $this->get_fields(),
		];
		
		return $meta_boxes;
	}

	/** Haalt formuliervelden op */
	protected function get_fields(): array {
		$fields = $this->form->get_form_fields();
		$fields = array_map( [ $this, 'parse_field'], $fields );

		// Voeg verzenden knop toe TODO: tekst configureerbaar maken?
		$fields[] = [
			'type'       => 'button',
			'columns'    => Form_Interface::FULL_WIDTH,
			'std'        => __( 'Verzenden', 'siw' ),
			'attributes' => [ 
				'type' => 'submit',
				'name' => 'rwmb_submit'
			],
		];
		
		return $fields;
	}

	/** Parset veld */
	protected function parse_field( array $field ): array {
		$defaults = [
			'required' => true,
			'columns'  => Form_Interface::HALF_WIDTH,
		];

		// TODO: verplaatsen naar Compatibility/MetaBox
		if ( 'file' == $field['type'] ) {

			$field['max_file_uploads'] = 1;
			$field['attributes'] = [
				'accept' => 'application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'maxsize' => 10 * MB_IN_BYTES, //Maximum attachment size van MailJet TODO: verplaatsen naar MailJet klasse
			];
		}
		return wp_parse_args_recursive( $field, $defaults );
	}
}
