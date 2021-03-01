<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Multi_Page_Form as Multi_Page_Form_Interface;

use SIW\Properties;

/**
 * Contactformulier algemeen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Tailor_Made implements Form_Interface, Multi_Page_Form_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'op_maat';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Op Maat', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_pages(): array {
		return [
			__( 'Personalia', 'siw' ),
			__( 'Project', 'siw' ),
			__( 'Talenkennis', 'siw' ),
		];
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		return [
			[
				'slug'  => 'voornaam',
				'type'  => 'text',
				'label' => __( 'Voornaam', 'siw' ),
				'recipient_name' => true,
				'page'  => 1,
			],
			[
				'slug'  => 'achternaam',
				'type'  => 'text',
				'label' => __( 'Achternaam', 'siw' ),
				'recipient_name' => true,
				'page'  => 1,
			],
			[
				'slug'   => 'geboortedatum',
				'type'   => 'text',
				'label'  => __( 'Geboortedatum', 'siw' ),
				'config' => [
					'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
					'validation'  => 'date',
				],
				'page'  => 1,
			],
			[
				'slug'   => 'geslacht',
				'type'   => 'radio',
				'label'  => __( 'Geslacht', 'siw' ),
				'config' => [
					'inline' => true,
					'option' => \siw_get_genders(),
				],
				'page'  => 1,
			],
			[
				'slug'          => 'emailadres',
				'type'          => 'email',
				'label'         => __( 'Emailadres', 'siw' ),
				'primary_email' => true,
				'page'          => 1,
			],
			[
				'slug'     => 'telefoonnummer',
				'type'     => 'text',
				'label'    => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
				'config'   => [
					'type_override' => 'tel',
				],
				'page'  => 1,
			],
			[
				'slug'   => 'postcode',
				'type'   => 'text',
				'label'  => __( 'Postcode', 'siw' ),
				'config' => [
					'postcode_lookup' => 'postcode',
					'placeholder'     => '1234 AB',
					'validation'      => 'postcode',
				],
				'page'  => 1,
			],
			[
				'slug'   => 'huisnummer',
				'type'   => 'text',
				'label'  => __( 'Huisnummer', 'siw' ),
				'config' => [
					'postcode_lookup' => 'city',
				],
				'page'   => 1,
			],
			[
				'slug'   => 'straat',
				'type'   => 'text',
				'label'  => __( 'Straat', 'siw' ),
				'config' => [
					'postcode_lookup' => 'street',
				],
				'page'   => 1,
			],
			[
				'slug'   => 'woonplaats',
				'type'   => 'text',
				'label'  => __( 'Woonplaats', 'siw' ),
				'config' => [
					'postcode_lookup' => 'city',
				],
				'page'   => 1,
			],
			[
				'slug'   => 'motivatie',
				'type'   => 'paragraph',
				'label'  => __( 'Waarom zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'config' => [
					'rows' => 7,
				],
				'width'  => Form_Interface::FULL_WIDTH,
				'page'   => 2,
			],
			[
				'slug'   => 'bestemming',
				'type'   => 'paragraph',
				'label'  => __( 'In welk land of welke regio zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'config' => [
					'rows' => 7,
				],
				'width'  => Form_Interface::FULL_WIDTH,
				'page'   => 2,
			],
			[
				'slug'   => 'periode',
				'type'   => 'paragraph',
				'label'  => __( 'In welke periode zou je op avontuur willen?', 'siw' ),
				'config' => [
					'rows' => 7,
				],
				'width'  => Form_Interface::FULL_WIDTH,
				'page'   => 2,
			],
			[
				'slug'   => 'cv',
				'type'   => 'file',
				'label'  => __( 'Upload hier je CV (optioneel)', 'siw'),
				'config' => [
					'attach'     => true,
					'media_lib'  => false,
					'allowed'    => 'pdf,docx',
					'max_upload' => \wp_max_upload_size(),
				],
				'required' => false,
				'width'    => Form_Interface::FULL_WIDTH,
				'page'     => 2,
			],
			[
				'slug'    => 'taal_1',
				'type'    => 'dropdown',
				'label'   => __( 'Taal 1', 'siw' ),
				'config'  => [
					'option'      => \siw_get_languages_list( 'volunteer' ),
					'placeholder' => __( 'Selecteer een taal', 'siw' ),
				],
				'page'    => 3,
			],
			[
				'slug'   => 'taal_1_niveau',
				'type'   => 'radio',
				'label'  => __( 'Niveau taal 1', 'siw' ),
				'config' => [
					'inline' => true,
					'option' => \siw_get_language_skill_levels(),
				],
				'page'   => 3,
			],
			[
				'slug'    => 'taal_2',
				'type'    => 'dropdown',
				'label'   => __( 'Taal 2', 'siw' ),
				'config'  => [
					'option'      => \siw_get_languages_list( 'volunteer' ),
					'placeholder' => __( 'Selecteer een taal', 'siw' ),
				],
				'page'   => 3,
			],
			[
				'slug'   => 'taal_2_niveau',
				'type'   => 'radio',
				'label'  => __( 'Niveau taal 2', 'siw' ),
				'config' => [
					'inline' => true,
					'option' => \siw_get_language_skill_levels(),
				],
				'page'   => 3,
			],
			[
				'slug'    => 'taal_3',
				'type'    => 'dropdown',
				'label'   => __( 'Taal 3', 'siw' ),
				'config'  => [
					'option'      => \siw_get_languages_list( 'volunteer' ),
					'placeholder' => __( 'Selecteer een taal', 'siw' ),
				],
				'page'   => 3,
			],
			[
				'slug'   => 'taal_3_niveau',
				'type'   => 'radio',
				'label'  => __( 'Niveau taal 3', 'siw' ),
				'config' => [
					'inline' => true,
					'option' => \siw_get_language_skill_levels(),
				],
				'page'   => 3,
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Aanmelding Vrijwilligerswerk Op Maat';

	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is onderstaande aanmelding voor Vrijwilligerswerk Op Maat binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Aanmelding Vrijwilligerswerk Op Maat', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor je aanmelding!', 'siw' ) . SPACE .
		 __( 'Leuk dat je hebt gekozen via SIW een Project Op Maat te doen.', 'siw' ) . SPACE .
		__( 'Wij zullen ons best gaan doen om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt.', 'siw' ) . BR2 .
		__( 'Onderaan deze e-mail vind je een overzicht van de gegevens zoals je die op het inschrijfformulier hebt ingevuld.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Wat gaat er nu gebeuren?', 'siw' ) .
		'</span>' . BR .
		__( 'Jouw aanmelding voor Vrijwilligerswerk Op Maat wordt doorgestuurd naar onze SIW-regiospecialisten.', 'siw' ) . SPACE .
		__( 'Vervolgens neemt één van de regiospecialisten contact met je op om een kennismakingsgesprek in te plannen.', 'siw' ) . SPACE .
		__( 'Houd er rekening mee dat SIW met vrijwilligers werkt, waardoor het contact soms iets langer kan duren.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Kennismakingsgesprek', 'siw' ) .
		'</span>' . BR .
		__( 'Tijdens het kennismakingsgesprek gaat onze regiospecialist samen met jou kijken welk Project Op Maat het beste bij jouw wensen en voorkeuren aansluit.', 'siw' ) . SPACE .
		__( 'In dit gesprek komen ook thema’s naar voren zoals interesse in culturen, creativiteit, flexibiliteit, enthousiasme en reis- en vrijwilligerswerkervaring.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Voorbereidingsdag', 'siw' ) .
		'</span>' . BR .
		__( 'Na het kennismakingsgesprek nodigen we je uit voor een voorbereidingsdag.', 'siw' ) . SPACE .
		__( 'Mocht je nog geen keuze hebben gemaakt voor een project, dan kan de voorbereiding je helpen in het bepalen wat jij belangrijk vindt.', 'siw' ) . SPACE .
		__( 'Tijdens de voorbereiding krijg je informatie over de continenten, landen, cultuurverschillen en gezondheidszorg.', 'siw' ) . SPACE .
		__( 'Ook wordt er stilgestaan bij jouw verwachtingen, praktische projectsituatie en het zelfstandig verblijven in het buitenland.', 'siw' ) . SPACE .
		__( 'Tijdens de voorbereiding zullen gastsprekers en oud-deelnemers aanwezig zijn.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Meer informatie', 'siw' ) .
		'</span>' . BR .
		sprintf( __( 'Als je nog vragen hebt, aarzel dan niet om contact op te nemen met ons kantoor via %s of via het nummer %s.', 'siw' ), Properties::EMAIL, Properties::PHONE );
	}


}


