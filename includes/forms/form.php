<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Util\Logger;
use SIW\Util\Meta_Box;

class Form extends Base {

	private const API_VERSION = 'v1';

	protected function __construct( protected Form_Interface $form ) {}

	#[Add_Filter( 'siw_forms' )]
	/** Registreer formulier TODO: registry ipv filter*/
	public function register_form( array $forms ): array {
		$forms[ $this->form->get_form_id() ] = $this->form->get_form_name();
		return $forms;
	}

	#[Add_Action( 'rest_api_init' )]
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
					'validate_callback'   => [ $this, 'validate' ],
				],
			]
		);
	}

	protected function get_namespace(): string {
		return 'siw/' . self::API_VERSION;
	}

	public function get_route(): string {
		$id = str_replace( '_', '-', $this->form->get_form_id() );
		return "form/{$id}";
	}

	public function verify_nonce( \WP_REST_Request $request ): bool {
		$nonce = $request->get_param( "nonce_siw_form_{$this->form->get_form_id()}" );
		return boolval( wp_verify_nonce( $nonce, "rwmb-save-siw_form_{$this->form->get_form_id()}" ) );
	}

	public function validate( \WP_REST_Request $request ): \WP_Error|bool {

		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );

		// Check quiz
		$quiz = sanitize_text_field( $request->get_param( 'quiz' ) );
		$quiz_hash = sanitize_text_field( $request->get_param( 'quiz_hash' ) );

		if ( siw_hash( $quiz ) !== $quiz_hash ) {
			Logger::info( "Quiz verkeerd ingevuld in formulier '{$this->form->get_form_id()}' vanaf IP {$ip}", __METHOD__ );
			return new \WP_Error(
				'incorrect_quiz',
				__( 'Dat is helaas niet het goede antwoord, sad.', 'siw' ),
				[ 'status' => \WP_Http::BAD_REQUEST ]
			);
		}

		// Check rate limit
		$transient_name = "siw_form_{$this->form->get_form_id()}_{$ip}";
		$submission_count = (int) get_transient( $transient_name );
		if ( $submission_count > 1 ) {
			++$submission_count;
			set_transient( $transient_name, $submission_count, $submission_count * HOUR_IN_SECONDS );
			Logger::info( "Meerdere aanmeldingen ({$submission_count}) in korte tijd voor formulier '{$this->form->get_form_id()}' vanaf IP {$ip}", __METHOD__ );
			return new \WP_Error(
				'duplicate_submission',
				__( 'Je hebt dit formulier al ingevuld.', 'siw' ),
				[ 'status' => \WP_Http::BAD_REQUEST ]
			);
		}
		set_transient( $transient_name, 1, HOUR_IN_SECONDS );

		return true;
	}

	public function callback( \WP_REST_Request $request ): \WP_REST_Response {
		$processor = new Processor( $this->form, $request );
		return $processor->process();
	}

	protected function get_args(): array {
		$args = array_map(
			[ Meta_Box::class, 'convert_field_to_rest_api_arg' ],
			array_column( $this->form->get_form_fields(), null, 'id' )
		);

		// Lege waardes verwijderen
		return array_filter( $args );
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
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

	protected function get_fields(): array {
		$fields = $this->form->get_form_fields();
		$fields = array_map( [ $this, 'parse_field' ], $fields );

		$fields = $this->add_quiz( $fields );

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

	protected function add_quiz( array $fields ): array {

		$one = wp_rand( 2, 5 );
		$two = wp_rand( 2, 5 );

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
			'id'      => 'quiz_hash',
			'type'    => 'hidden',
			'std'     => siw_hash( (string) $answer ),
			'columns' => Form_Interface::FULL_WIDTH,
		];

		return $fields;
	}

	protected function parse_field( array $field ): array {
		$defaults = [
			'required' => true,
			'columns'  => Form_Interface::HALF_WIDTH,
		];
		return wp_parse_args_recursive( $field, $defaults );
	}
}
