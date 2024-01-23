<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

class Enquiry_General implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	public const FORM_ID = 'enquiry_general';

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Infoverzoek algemeen', 'siw' );
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
				'name' => __( 'E-mailadres', 'siw' ),
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
	public function get_notification_mail_message(): string {
		return 'Via de website is een vraag gesteld:';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Informatieverzoek {{ first_name }} {{ last_name }}';
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		__( 'Bedankt voor het invullen van ons contactformulier.', 'siw' ) . SPACE .
		__( 'Wij hebben je vraag ontvangen en we nemen zo snel mogelijk contact met je op.', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging informatieverzoek', 'siw' );
	}
}
