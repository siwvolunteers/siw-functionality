<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Email_Settings;
use SIW\Facades\Meta_Box;
use SIW\Helpers\Email;
use SIW\Helpers\Email_Template;
use SIW\Jobs\Async\Export_To_Mailjet;
use SIW\Properties;

abstract class Form extends Base {

	private const API_VERSION = 'v1';

	//TODO: enum
	public const FULL_WIDTH = 12;
	public const HALF_WIDTH = 6;
	public const THIRD_WIDTH = 4;
	public const QUARTER_WIDTH = 3;


	protected \WP_REST_Request $request;

	final public static function get_id(): string {
		$class_name_components = explode( '\\', static::class );
		return strtolower( end( $class_name_components ) );
	}

	abstract protected function get_name(): string;
	abstract protected function get_fields(): array;

	protected function should_send_notification_mail(): bool {
		return true;
	}

	protected function should_export_to_mailjet(): bool {
		return false;
	}

	protected function get_mailjet_list_id( \WP_REST_Request $request ): ?int {
		return null;
	}

	protected function get_mailjet_properties( \WP_REST_Request $request ): array {
		return [];
	}

	protected function get_confirmation_mail_subject( \WP_REST_Request $request ): string {
		// translators: %s is de naam van het formulier
		return sprintf( __( 'Bevestiging %s', 'siw' ), $this->get_name() );
	}

	protected function get_notification_mail_subject( \WP_REST_Request $request ): string {
		return $this->get_name();
	}

	#[Add_Filter( 'siw_forms' )]
	public function register_form( array $forms ): array {
		$forms[ static::get_id() ] = $this->get_name();
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
		$id = str_replace( '_', '-', $this->get_id() );
		return "form/{$id}";
	}

	public function verify_nonce( \WP_REST_Request $request ): bool {
		$nonce = $request->get_param( "nonce_siw_form_{$this->get_id()}" );
		return boolval( wp_verify_nonce( $nonce, "rwmb-save-siw_form_{$this->get_id()}" ) );
	}

	public function validate( \WP_REST_Request $request ): \WP_Error|bool {
		$quiz_check = Quiz::check( $request, $this->get_id() );
		if ( is_wp_error( $quiz_check ) ) {
			return $quiz_check;
		}

		$submission_rate_limit_check = Submission_Rate_Limit::check( $request, $this->get_id() );
		if ( is_wp_error( $submission_rate_limit_check ) ) {
			return $submission_rate_limit_check;
		}

		return true;
	}

	protected function get_args(): array {
		$args = array_map(
			[ Meta_Box::class, 'convert_field_to_rest_api_arg' ],
			array_column( $this->get_fields(), null, 'id' )
		);

		// Lege waardes verwijderen
		return array_filter( $args );
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'          => "siw_form_{$this->get_id()}",
			'title'       => "{$this->get_name()}",
			'post_types'  => [],
			'toggle_type' => 'slide',
			'fields'      => $this->get_all_fields(),
		];

		return $meta_boxes;
	}

	protected function get_all_fields(): array {
		$fields = $this->get_fields();
		$fields = array_map( [ $this, 'parse_field' ], $fields );
		$fields = Quiz::add_quiz_fields( $fields );

		$fields[] = [
			'type'       => 'button',
			'columns'    => self::FULL_WIDTH,
			'std'        => __( 'Verzenden', 'siw' ),
			'attributes' => [
				'type' => 'submit',
				'name' => 'rwmb_submit',
			],
		];

		return $fields;
	}

	protected function parse_field( array $field ): array {
		$defaults = [
			'required' => true,
			'columns'  => self::HALF_WIDTH,
		];
		return wp_parse_args_recursive( $field, $defaults );
	}

	public function callback( \WP_REST_Request $request ): \WP_REST_Response {
		$this->send_confirmation_mail( $request );
		if ( $this->should_send_notification_mail() ) {
			$this->send_notification_mail( $request );
		}

		if ( $this->should_export_to_mailjet() ) {
			$this->export_to_mailjet( $request );
		}

		return new \WP_REST_Response(
			[
				'message' => __( 'Je bericht werd succesvol verzonden.', 'siw' ),
			],
			\WP_Http::OK
		);
	}

	protected function send_confirmation_mail( \WP_REST_Request $request ) {
		$confirmation_message = Email_Template::create()
			->set_signature( __( 'SIW', 'siw' ) )
			->set_template( 'forms/' . $this->get_id() )
			->add_context( $this->get_template_context( $request ) )
			->set_subject( $this->get_confirmation_mail_subject( $request ) )
			->add_table_data( $this->get_summary_data( $request ), __( 'Ingevulde gegevens', 'siw' ) )
			->generate();

		return Email::create()
			->set_subject( $this->get_confirmation_mail_subject( $request ) )
			->set_message( $confirmation_message )
			->add_recipient( $this->get_customer_email( $request ) )
			->set_from( $this->get_email_settings()->get_confirmation_mail_sender(), Properties::NAME )
			->set_content_type( Email::TEXT_HTML )
			->send();
	}

	protected function send_notification_mail( \WP_REST_Request $request ) {
		$notification_message = Email_Template::create()
			->set_subject( $this->get_notification_mail_subject( $request ) )
			->set_template( 'forms/notification' )
			->add_context( $this->get_template_context( $request ) )
			->add_table_data( $this->get_summary_data( $request ), __( 'Ingevulde gegevens', 'siw' ) )
			->generate();

		$notification_mail = Email::create()
			->set_subject( $this->get_notification_mail_subject( $request ) )
			->set_message( $notification_message )
			->add_recipient( $this->get_email_settings()->get_notification_mail_recipient() )
			->set_from( Properties::EMAIL, __( 'Website', 'siw' ) )
			->set_reply_to( $this->get_customer_email( $request ) )
			->set_content_type( Email::TEXT_HTML );

		$notification_mail->send();
	}

	protected function get_summary_data( \WP_REST_Request $request ): array {
		foreach ( $this->get_fields() as $field ) {
			if ( 'hidden' === $field['type'] ) {
				continue;
			}

			$raw_value = $request->get_param( $field['id'] );
			if ( empty( $raw_value ) ) {
				continue;
			}
			$summary_data[ $field['name'] ] = Meta_Box::format_value( $field, $raw_value );
		}
		return $summary_data;
	}

	protected function get_template_context( \WP_REST_Request $request ): array {
		foreach ( $this->get_fields() as $field ) {
			$raw_value = $request->get_param( $field['id'] );
			if ( empty( $raw_value ) ) {
				continue;
			}
			$context[ $field['id'] ] = Meta_Box::format_value( $field, $raw_value );
		}

		$context['form_name'] = $this->get_name();
		$post_id = url_to_postid( home_url( $request->get_param( '_wp_http_referer' ) ) );
		if ( 0 !== $post_id ) {
			$context['page_url'] = get_permalink( $post_id );
			$context['page_title'] = get_the_title( $post_id );
		}

		return $context;
	}

	protected function get_customer_email( \WP_REST_Request $request ): ?string {
		$email_fields = wp_list_filter( $this->get_fields(), [ 'type' => 'email' ] );
		$customer_email_field = reset( $email_fields );
		return $request->get_param( $customer_email_field['id'] );
	}

	protected function get_email_settings(): Email_Settings {
		return siw_get_email_settings( $this->get_id() );
	}

	protected function export_to_mailjet( \WP_REST_Request $request ) {
		$data = [
			'email'      => $this->get_customer_email( $request ),
			'list_id'    => $this->get_mailjet_list_id( $request ),
			'properties' => array_filter( $this->get_mailjet_properties( $request ) ),
		];

		as_enqueue_async_action( Export_To_Mailjet::class, $data );
	}
}
