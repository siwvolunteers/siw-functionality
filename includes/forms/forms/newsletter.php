<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\i18n;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;

use SIW\Util\Links;
use SIW\Widgets\Newsletter_Confirmation;

/**
 * Aanmelding nieuwsbrief
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Newsletter implements Form_Interface, Confirmation_Mail_Interface {

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return 'newsletter';
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Aanmelding nieuwsbrief', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_form_fields(): array {
		$fields = [
			[
				'id'      => 'first_name',
				'name'    => __( 'Voornaam', 'siw' ),
				'type'    => 'text',
			],
			[
				'id'      => 'email',
				'name'    => __( 'E-mailadres', 'siw' ),
				'type'    => 'email',
			],
		];
		return $fields;
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestig je aanmelding voor onze nieuwsbrief', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}'). BR2 .
				__( 'Bedankt voor je aanmelding voor de SIW-nieuwsbrief!', 'siw' ) . SPACE .
				__( 'Om zeker te weten dat je inschrijving correct is, vragen we je je aanmelding te bevestigen.', 'siw' ) . BR2 .
				Links::generate_link(
					$this->generate_confirmation_url(),
					__( 'Klik hier om je aanmelding voor onze nieuwsbrief direct te bevestigen.', 'siw' )
				);
	}
	
	/** Genereert url voor bevestingslink TODO: verplaatsen naar Newsletter Util klasse */
	protected function generate_confirmation_url(): string {

		$confirmation_page = i18n::get_translated_page_url( (int) siw_get_option( 'pages.newsletter_confirmation') );

		return add_query_arg(
			[
				Newsletter_Confirmation::QUERY_ARG_EMAIL           => '{{ email | base64_encode | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_EMAIL_HASH      => '{{ email | siw_hash | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME      => '{{ first_name | base64_encode | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME_HASH => '{{ first_name | siw_hash | urlencode }}',
			],
			untrailingslashit( $confirmation_page )
		);
	}
}