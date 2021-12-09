<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Aanmeldformulier infodag
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Leader_Dutch_Projects implements Form_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'begeleider_np';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Projectbegeleider NP', 'siw' );
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
				'slug'   => 'geboortedatum',
				'type'   => 'text',
				'label'  => __( 'Geboortedatum', 'siw' ),
				'config' => [
					'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
					'validation'  => 'date',
				],
			],
			[
				'slug'   => 'voorkeur',
				'type'   => 'checkbox',
				'label'  => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
				'config' => [
					'option' => $this->get_project_options(),
				]
			],

			[
				'slug'   => 'motivatie',
				'type'   => 'paragraph',
				'label'  => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
				'config' => [
					'rows' => 7,
				],
				'width'  => Form_Interface::FULL_WIDTH,
			],

		];
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Aanmelding projectbegeleider';
	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Bevestiging aanmelding', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			__( 'Bedankt voor jouw aanmelding.', 'siw') . SPACE .
			__( 'Wat leuk dat je interesse hebt om projectbegeleider te worden voor de Nederlandse vrijwilligersprojecten.', 'siw' ) . SPACE .
			__( 'Een creatieve uitdaging die je nooit meer zal vergeten!', 'siw' ) . SPACE .
			__( 'Zoals oud-projectbegeleider Diederik (project in Friesland) het omschreef:', 'siw' ) . BR .
			'<span style="font-style:italic">"'.
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
			$project_options[ sanitize_title( $project->get_sku() ) ] = $project->get_attribute( 'Projectnaam' );
		}
		return $project_options;
	}
}
