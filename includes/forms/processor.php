<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Data\Email_Settings;
use SIW\Helpers\Email;
use SIW\Helpers\Email_Template;
use SIW\Helpers\Spam_Check;
use SIW\Helpers\Template;
use SIW\Interfaces\Forms\Confirmation_Mail;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail;
use SIW\Properties;
use SIW\Util\Meta_Box;

/**
 * Class om een formulieraanmelding te verwerken
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Processor {

	/** Bevestigingsmail */
	protected Confirmation_Mail $confirmation_mail;

	/** Notificatiemail */
	protected Notification_Mail $notification_mail;

	/** IP adres */
	protected string $ip;

	/** Init */
	public function __construct( protected Form_Interface $form, protected \WP_REST_Request $request ) {

		if ( is_a( $form, Confirmation_Mail::class ) ) {
			$this->confirmation_mail = $this->form;
		}

		if ( is_a( $form, Notification_Mail::class ) ) {
			$this->notification_mail = $this->form;
		}
	}

	/** Verwerken */
	public function process(): \WP_REST_Response {

		$this->ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );

		// Afbreken als het antwoord op de quiz niet klopt
		if ( ! $this->check_quiz_answer() ) {
			return new \WP_REST_Response(
				[
					'message' => __( 'Dat is helaas niet het goede antwoord.', 'siw' ),
				],
				\WP_Http::BAD_REQUEST
			);
		}

		if ( ! $this->check_rate_limit() ) {
			return new \WP_REST_Response(
				[
					'message' => __( 'Je hebt dit formulier al ingevuld.', 'siw' ),
				],
				\WP_Http::BAD_REQUEST
			);
		}

		// Afbreken als het spam is
		if ( $this->is_spam() ) {
			return new \WP_REST_Response(
				[
					'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
				],
				\WP_Http::BAD_REQUEST
			);
		}

		// TODO: Verwerk file uploads

		// TODO: onderstaande async actie(s) van maken?
		if ( isset( $this->confirmation_mail ) ) {
			$this->send_confirmation_mail();
		}

		if ( isset( $this->notification_mail ) ) {
			$this->send_notification_mail();
		}
		// TODO: Verwijder file uploads

		return new \WP_REST_Response(
			[
				'message' => __( 'Je bericht werd succesvol verzonden.', 'siw' ),
			],
			\WP_Http::OK
		);
	}

	/** Parset Mustache string template */
	protected function parse_template( string $string_template ): string {
		return Template::create()->set_template( $string_template )->set_context( $this->get_template_context() )->parse_template();
	}

	/** Geeft gegevens voor samenvatting terug */
	protected function get_summary_data(): array {
		foreach ( $this->form->get_form_fields() as $field ) {
			$raw_value = $this->request->get_param( $field['id'] );
			if ( empty( $raw_value ) ) {
				continue;
			}
			$summary_data[ $field['name'] ] = Meta_Box::get_display_value( $field, $raw_value );
		}
		return $summary_data;
	}

	/** Geeft template context voor Mustache templates terug */
	protected function get_template_context(): array {
		foreach ( $this->form->get_form_fields() as $field ) {
			$raw_value = $this->request->get_param( $field['id'] );
			if ( empty( $raw_value ) ) {
				continue;
			}
			$context[ $field['id'] ] = Meta_Box::get_display_value( $field, $raw_value );
		}

		$post_id = url_to_postid( home_url( $this->request->get_param( '_wp_http_referer' ) ) );

		if ( 0 !== $post_id ) {
			$context['page_url'] = get_permalink( $post_id );
			$context['page_title'] = get_the_title( $post_id );
		}

		return $context;
	}

	/** Geef email adres van klant terug */
	protected function get_customer_email(): ?string {
		$email_fields = wp_list_filter( $this->form->get_form_fields(), [ 'type' => 'email' ] );
		$customer_email_field = reset( $email_fields );
		return $this->request->get_param( $customer_email_field['id'] );
	}

	/** Haal e-mail instelling op */
	protected function get_email_settings(): Email_Settings {
		$email_settings = siw_get_email_settings( $this->form->get_form_id() );
		return $email_settings;
	}

	/** Verstuurt bevestigingsmail naar klant */
	protected function send_confirmation_mail() {

		// Bevestigings email naar klant
		$confirmation_message = Email_Template::create()
			->set_signature( __( 'SIW', 'siw' ) )
			->set_message( $this->parse_template( $this->confirmation_mail->get_confirmation_mail_message() ) )
			->set_subject( $this->parse_template( $this->confirmation_mail->get_confirmation_mail_subject() ) )
			->set_summary_data( $this->get_summary_data() )
			->generate();

		return Email::create()
			->set_subject( $this->parse_template( $this->confirmation_mail->get_confirmation_mail_subject() ) )
			->set_message( $confirmation_message )
			->add_recipient( $this->get_customer_email() )
			->set_from( $this->get_email_settings()->get_confirmation_mail_sender(), Properties::NAME )
			->set_content_type( Email::TEXT_HTML )
			->send();
	}

	/** Verstuurt notificatiemail naar kantoor */
	protected function send_notification_mail() {
		$notification_message = Email_Template::create()
			->set_subject( $this->parse_template( $this->notification_mail->get_notification_mail_subject() ) )
			->set_message( $this->parse_template( $this->notification_mail->get_notification_mail_message() ) )
			->set_summary_data( $this->get_summary_data() )
			->generate();

		$notification_mail = Email::create()
			->set_subject( $this->parse_template( $this->notification_mail->get_notification_mail_subject() ) )
			->set_message( $notification_message )
			->add_recipient( $this->get_email_settings()->get_notification_mail_recipient() )
			->set_from( Properties::EMAIL, __( 'Website', 'siw' ) )
			->set_reply_to( $this->get_customer_email() )
			->set_content_type( Email::TEXT_HTML );

		$notification_mail->send();
	}

	/** Controleer het antwoord van de quiz */
	protected function check_quiz_answer(): bool {
		$quiz = sanitize_text_field( $this->request->get_param( 'quiz' ) );
		$quiz_hash = sanitize_text_field( $this->request->get_param( 'quiz_hash' ) );

		return siw_hash( $quiz ) === $quiz_hash;
	}

	/** Checkt de rate limite */
	protected function check_rate_limit(): bool {
		$transient_name = "siw_form_{$this->form->get_form_id()}_{$this->ip}";
		$submission_count = (int) get_transient( $transient_name );
		if ( $submission_count > 0 ) {
			$submission_count ++;
			set_transient( $transient_name, $submission_count, $submission_count * HOUR_IN_SECONDS );
			return false;
		}
		set_transient( $transient_name, 1, HOUR_IN_SECONDS );
		return true;
	}


	/** Check of het spam betreft */
	protected function is_spam(): bool {

		// Haal email adres op voor spam_check
		$email = $this->get_customer_email() ?? null;

		$spam_check = Spam_Check::create();
		if ( null !== $email ) {
			$spam_check->set_email( $email );
		}
		if ( ! empty( $this->ip ) ) {
			$spam_check->set_ip( $this->ip );
		}
		// TODO: check inhoud van bepaalde velden op links
		return $spam_check->is_spam();
	}
}
