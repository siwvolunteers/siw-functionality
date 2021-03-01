<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Samenwerkingsformulier
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Cooperation implements Form_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'samenwerking';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Samenwerking', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'slug'  => 'naam_organisatie',
				'type'  => 'text',
				'label' => __( 'Naam organisatie', 'siw' ),
				'width' => Form_Interface::FULL_WIDTH,
			],
			[
				'slug'           => 'naam_contactpersoon',
				'type'           => 'text',
				'label'          => __( 'Naam contactpersoon', 'siw' ),
				'width'          => Form_Interface::FULL_WIDTH,
				'recipient_name' => true,
			],
			[
				'slug'          => 'emailadres',
				'type'          => 'email',
				'label'         => __( 'Emailadres', 'siw' ),
				'width'         => Form_Interface::FULL_WIDTH,
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
				'width' => Form_Interface::FULL_WIDTH,
			],
			[
				'slug'   => 'toelichting',
				'type'   => 'paragraph',
				'label'  => __( 'Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw' ),
				'config' => [
					'rows' => 7,
				],
				'width' => Form_Interface::FULL_WIDTH,
			]
		];
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Interesse samenwerking';
	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is onderstaand bericht verstuurd:';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Bevestiging interesse samenwerking', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%naam_contactpersoon%' ) . BR2 .
		__( 'Wat leuk dat u interesse heeft in een samenwerking met SIW Internationale Vrijwilligersprojecten!', 'siw' ) . SPACE .
		__( 'Wij willen u bedanken voor het achterlaten van uw contactgegevens en wensen.', 'siw' ) . SPACE .
		__( 'Ons streven is binnen drie tot vijf werkdagen contact met u op te nemen om de mogelijkheden te bespreken.', 'siw' );
	}
}