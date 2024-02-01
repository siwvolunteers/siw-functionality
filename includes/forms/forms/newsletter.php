<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Config;
use SIW\Data\Special_Page;
use SIW\Elements\Link;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Widgets\Newsletter_Confirmation;

class Newsletter implements Form_Interface, Confirmation_Mail_Interface {

	public const FORM_ID = 'newsletter';

	#[\Override]
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	#[\Override]
	public function get_form_name(): string {
		return __( 'Aanmelding nieuwsbrief', 'siw' );
	}

	#[\Override]
	public function get_form_fields(): array {
		$fields = [
			[
				'id'   => 'first_name',
				'name' => __( 'Voornaam', 'siw' ),
				'type' => 'text',
			],
			[
				'id'   => 'email',
				'name' => __( 'E-mailadres', 'siw' ),
				'type' => 'email',
			],
			[
				'id'      => 'list_id',
				'type'    => 'hidden',
				'std'     => Config::get_mailjet_newsletter_list_id(),
				'columns' => Form_Interface::FULL_WIDTH,
			],
		];
		return $fields;
	}

	#[\Override]
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestig je aanmelding voor onze nieuwsbrief', 'siw' );
	}

	#[\Override]
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
				__( 'Bedankt voor je aanmelding voor de SIW-nieuwsbrief!', 'siw' ) . SPACE .
				__( 'Om zeker te weten dat je inschrijving correct is, vragen we je je aanmelding te bevestigen.', 'siw' ) . BR2 .
				Link::create()
					->set_url( $this->generate_confirmation_url() )
					->set_text( __( 'Klik hier om je aanmelding voor onze nieuwsbrief direct te bevestigen.', 'siw' ) )
					->generate();
	}

	protected function generate_confirmation_url(): string {

		$confirmation_page = Special_Page::NEWSLETTER_CONFIRMATION->get_page();

		return add_query_arg(
			[
				Newsletter_Confirmation::QUERY_ARG_EMAIL   => '{{ email | base64_encode | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_EMAIL_HASH => '{{ email | siw_hash | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME => '{{ first_name | base64_encode | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME_HASH => '{{ first_name | siw_hash | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_LIST_ID => '{{ list_id | base64_encode | urlencode }}',
				Newsletter_Confirmation::QUERY_ARG_LIST_ID_HASH => '{{ list_id | siw_hash | urlencode }}',
			],
			untrailingslashit( get_permalink( $confirmation_page ) )
		);
	}
}
