<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

class ESC implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	public const FORM_ID = 'esc';

	#[\Override]
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	#[\Override]
	public function get_form_name(): string {
		return __( 'Aanmelding ESC', 'siw' );
	}

	#[\Override]
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
				'id'          => 'date_of_birth',
				'type'        => 'text',
				'name'        => __( 'Geboortedatum', 'siw' ),
				'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
				'attributes'  => [
					'data-rule-dateNL' => true,
				],
			],
			[
				'id'   => 'city',
				'type' => 'text',
				'name' => __( 'Woonplaats', 'siw' ),
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
				'id'   => 'motivation',
				'type' => 'textarea',
				'name' => __( 'Waarom wil je graag aan een ESC-project deelnemen?', 'siw' ),
			],
			[
				'id'   => 'period',
				'type' => 'textarea',
				'name' => __( 'In welke periode zou je graag een ESC project willen doen?', 'siw' ),
			],
		];
	}

	#[\Override]
	public function get_notification_mail_subject(): string {
		return 'Aanmelding ESC';
	}

	#[\Override]
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaande ESC-aanmelding binnengekomen:';
	}

	#[\Override]
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging aanmelding ESC', 'siw' );
	}

	#[\Override]
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		__( 'Bedankt voor je ESC-aanmelding.', 'siw' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'We nemen zo snel mogelijk contact met je op om in een gesprek verder met je kennis te maken!', 'siw' );
	}
}
