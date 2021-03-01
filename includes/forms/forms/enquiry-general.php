<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Contactformulier algemeen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Enquiry_General implements Form_Interface {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'contact_algemeen';
	}

	/** {@inheritDoc} */
	public function get_name() : string {
		return __( 'Infoverzoek algemeen', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'slug'  => 'voornaam',
				'type'  => 'text',
				'label' => __( 'Voornaam', 'siw' ),
				'recipient_name' => true,
			],
			[
				'slug'  => 'achternaam',
				'type'  => 'text',
				'label' => __( 'Achternaam', 'siw' ),
			],
			[
				'slug'  => 'emailadres',
				'type'  => 'email',
				'label' => __( 'Emailadres', 'siw' ),
				'primary_email' => true,
			],
			[
				'slug'     => 'telefoonnummer',
				'type'     => 'text',
				'label'    => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
				'config'   => [
					'type_override' => 'tel',
				],
			],
			[
				'slug'  => 'vraag',
				'type'  => 'paragraph',
				'label' => __( 'Vraag', 'siw' ),
				'width' => Form_Interface::FULL_WIDTH,
			]
		];
	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is een vraag gesteld:';
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Informatieverzoek %voornaam% %achternaam%';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string{
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor het invullen van ons contactformulier.', 'siw' ) . SPACE .
		__( 'Wij hebben je vraag ontvangen en we nemen zo snel mogelijk contact met je op.', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Bevestiging informatieverzoek', 'siw' );
	}
}
