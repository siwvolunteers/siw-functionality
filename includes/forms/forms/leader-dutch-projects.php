<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

/**
 * Aanmelding projectbegeleider NP
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Leader_Dutch_Projects implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	/** Formulier ID */
	const FORM_ID = 'leader_dutch_projects';

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Aanmelding projectbegeleider NP', 'siw' );
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
				'id'          => 'date_of_birth',
				'type'        => 'text',
				'name'        => __( 'Geboortedatum', 'siw' ),
				'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
			],
			[
				'id'      => 'project_preference',
				'type'    => 'checkbox_list',
				'name'    => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
				'options' => $this->get_project_options(),
			],
			[
				'id'      => 'motivation',
				'type'    => 'textarea',
				'name'    => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
				'rows'    => 7,
				'columns' => Form_Interface::FULL_WIDTH,
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Aanmelding projectbegeleider';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging aanmelding', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
			__( 'Bedankt voor jouw aanmelding.', 'siw' ) . SPACE .
			__( 'Wat leuk dat je interesse hebt om projectbegeleider te worden voor de Nederlandse vrijwilligersprojecten.', 'siw' ) . SPACE .
			__( 'Een creatieve uitdaging die je nooit meer zal vergeten!', 'siw' ) . SPACE .
			__( 'Zoals oud-projectbegeleider Diederik (project in Friesland) het omschreef:', 'siw' ) . BR .
			'<span style="font-style:italic">"' .
			__( 'Het is ontzettend leerzaam om met zoveel verschillende mensen om te gaan, iedereen gemotiveerd te houden en te zorgen dat iedereen zich op zijn gemak voelt.', 'siw' ) . SPACE .
			__( 'Daarnaast zie je hoe de groep zich ontwikkelt, een prachtig proces om van zo dichtbij mee te mogen maken.', 'siw' ) .
			'"</span>' . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Hoe gaat het nu verder?', 'siw' ) .
			'</span>' . BR .
			__( 'Wij werven doorgaans in de maanden maart tot en met mei projectbegeleiders om de zomerprojecten te begeleiden.', 'siw' ) . SPACE .
			__( 'Mocht jij je in deze periode hebben aangemeld, dan zullen wij contact met je opnemen.', 'siw' ) . SPACE .
			__( 'Ligt jouw aanmelding buiten onze wervingsperiode? Geen probleem.', 'siw' ) . SPACE .
			__( 'Wij voegen jouw aanmelding toe aan onze database voor een volgend zomerseizoen.', 'siw' );
	}

	/** Geeft lijst met Nederlandse projecten terug */
	protected function get_project_options(): array {
		$project_options[''] = __( 'Geen voorkeur', 'siw' );

		$args = [
			'country' => 'nederland',
		];
		$projects = siw_get_products( $args );

		foreach ( $projects as $project ) {
			$project_options[ sanitize_title( $project->get_sku() ) ] = $project->get_formatted_name();
		}
		return $project_options;
	}
}
