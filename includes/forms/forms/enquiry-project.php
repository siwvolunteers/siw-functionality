<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

/**
 * Infoverzoek project
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Enquiry_Project implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return 'enquiry_project';
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Infoverzoek Groepsproject', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_form_fields(): array {
		return [
			[
				'id'   => 'first_name',
				'type' => 'text',
				'name' => __( 'Voornaam', 'siw' ),
			],
			[
				'id'   => 'last_name',
				'type' => 'text',
				'name' => __( 'Achternaam', 'siw' ),
			],
			[
				'id'   => 'email',
				'type' => 'email',
				'name' => __( 'Emailadres', 'siw' ),
			],
			[
				'id'       => 'phone',
				'type'     => 'tel',
				'name'     => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
			],
			[
				'id'      => 'question',
				'type'    => 'textarea',
				'name'    => __( 'Vraag', 'siw' ),
				'columns' => Form_Interface::FULL_WIDTH,
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Informatieverzoek project {{ first_name }} {{ last_name }}';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_message(): string {
		return sprintf(
			'Via de website is een vraag gesteld over het project %s',
			'{{ page_title }} (<a href="{{ page_url }}" target="_blank" style="text-decoration:none">{{ page_url }}<a/>)<br/>'
		);
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging informatieverzoek', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		// translators: %s is link naar het project
			sprintf( __( 'Leuk om te zien dat je interesse hebt getoond in het project %s.', 'siw' ), '<a href="{{ page_url }}" target="_blank" style="text-decoration:none">{{ page_title }}<a/>' ) . SPACE .
			__( 'Je hebt ons een vraag gesteld.', 'siw' ) . SPACE .
			__( 'Wellicht was er iets niet helemaal duidelijk of wil je graag meer informatie ontvangen.', 'siw' ) . SPACE .
			__( 'Wat de reden ook was, wij helpen je graag verder.', 'siw' ) . SPACE .
			__( 'We nemen zo snel mogelijk contact met je op.', 'siw' );
	}
}
