<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Contactformulier algemeen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Enquiry_Project implements Form_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'contact_project';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Infoverzoek Groepsproject', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'slug'           => 'voornaam',
				'type'           => 'text',
				'label'          => __( 'Voornaam', 'siw' ),
				'recipient_name' => true,
			],
			[
				'slug'           => 'achternaam',
				'type'           => 'text',
				'label'          => __( 'Achternaam', 'siw' ),
				'recipient_name' => true,
			],
			[
				'slug'          => 'emailadres',
				'type'          => 'email',
				'label'         => __( 'Emailadres', 'siw' ),
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
	public function get_notification_subject(): string {
		return sprintf( 'Informatieverzoek project %s', '{embed_post:post_title}' );
	}
	
	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return sprintf(
			'Via de website is een vraag gesteld over het project %s',
			'{embed_post:post_title} (<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:permalink}<a/>)<br/>'
		);
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Bevestiging informatieverzoek', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			sprintf( __( 'Leuk om te zien dat je interesse hebt getoond in het project %s.', 'siw' ), '<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:post_title}<a/>') . SPACE .
			__( 'Je hebt ons een vraag gesteld.', 'siw' ) . SPACE .
			__( 'Wellicht was er iets niet helemaal duidelijk of wil je graag meer informatie ontvangen.', 'siw' ) . SPACE .
			__( 'Wat de reden ook was, wij helpen je graag verder.', 'siw' ) . SPACE .
			__( 'We nemen zo snel mogelijk contact met je op.', 'siw' );
	}
}
