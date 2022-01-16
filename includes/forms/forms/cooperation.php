<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

/**
 * Samenwerkingsformulier
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Cooperation implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return 'samenwerking';
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Samenwerking', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_form_fields(): array {
		return [
			[
				'id'      => 'organisation',
				'type'    => 'text',
				'name'    => __( 'Naam organisatie', 'siw' ),
			],
			[
				'id'      => 'contact_person',
				'type'    => 'text',
				'name'    => __( 'Naam contactpersoon', 'siw' ),
			],
			[
				'id'      => 'email',
				'type'    => 'email',
				'name'    => __( 'Emailadres', 'siw' ),
			],
			[
				'id'       => 'phone',
				'type'     => 'tel',
				'name'     => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
			],
			[
				'id'       => 'explanation',
				'type'     => 'textarea',
				'name'     => __( 'Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw' ),
				'required' => true,
				'columns'  => Form_Interface::FULL_WIDTH,
			]
		];
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Interesse samenwerking';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaand bericht verstuurd:';
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging interesse samenwerking', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ contact_person }}' ) . BR2 .
		__( 'Wat leuk dat u interesse heeft in een samenwerking met SIW Internationale Vrijwilligersprojecten!', 'siw' ) . SPACE .
		__( 'Wij willen u bedanken voor het achterlaten van uw contactgegevens en wensen.', 'siw' ) . SPACE .
		__( 'Ons streven is binnen drie tot vijf werkdagen contact met u op te nemen om de mogelijkheden te bespreken.', 'siw' );
	}
}
