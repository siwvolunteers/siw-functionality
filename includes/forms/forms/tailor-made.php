<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

use SIW\Properties;

/**
 * Aanmelding Op Maat
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Tailor_Made implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	/** Formulier ID */
	public const FORM_ID = 'tailor_made';

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Aanmelding Wereld-basis', 'siw' );
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
				'id'   => 'date_of_birth',
				'type' => 'date',
				'name' => __( 'Geboortedatum', 'siw' ),
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
				'name' => __( 'Waarom zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'rows' => 7,
			],
			[
				'id'      => 'destination',
				'type'    => 'radio',
				'name'    => __( 'In welke regio zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'options' => $this->get_destination_options(),
			],
			[
				'id'      => 'duration',
				'type'    => 'radio',
				'name'    => __( 'Hoe lang zou je weg willen?', 'siw' ),
				'options' => $this->get_duration_options(),
			],
		];
	}

	/** Geeft opties voor projectduur terug */
	protected function get_duration_options(): array {
		return [
			'1-3'  => __( '1-3 maanden', 'siw' ),
			'3-6'  => __( '4-6 maanden', 'siw' ),
			'7-12' => __( '7-12 maanden', 'siw' ),
		];
	}

	protected function get_destination_options(): array {
		$destinations = siw_get_continents_list();
		unset( $destinations['noord-amerika'] );
		unset( $destinations['europa'] );
		return $destinations;
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Aanmelding Wereld-basis';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaande aanmelding voor Wereld-basis binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return __( 'Bevestiging aanmelding Wereld-basis', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		__( 'Bedankt voor je aanmelding!', 'siw' ) . SPACE .
		__( 'Leuk dat je hebt gekozen via SIW een Wereld-basis-project te doen.', 'siw' ) . SPACE .
		__( 'Wij zullen ons best gaan doen om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt.', 'siw' ) . BR2 .
		__( 'Onderaan deze e-mail vind je een overzicht van de gegevens zoals je die op het inschrijfformulier hebt ingevuld.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Wat gaat er nu gebeuren?', 'siw' ) .
		'</span>' . BR .
		__( 'Jouw aanmelding voor Wereld-basis wordt doorgestuurd naar onze SIW-regiospecialisten.', 'siw' ) . SPACE .
		__( 'Vervolgens neemt één van de regiospecialisten contact met je op om een kennismakingsgesprek in te plannen.', 'siw' ) . SPACE .
		__( 'Houd er rekening mee dat SIW met vrijwilligers werkt, waardoor het contact soms iets langer kan duren.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Kennismakingsgesprek', 'siw' ) .
		'</span>' . BR .
		__( 'Tijdens het kennismakingsgesprek gaat onze regiospecialist samen met jou kijken welk Wereld-basis-project het beste bij jouw wensen en voorkeuren aansluit.', 'siw' ) . SPACE .
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
		// translators: %1$s is het emailadres van SIW, %2$s is het telefoonnummer
		sprintf( __( 'Als je nog vragen hebt, aarzel dan niet om contact op te nemen met ons kantoor via %1$s of via het nummer %2$s.', 'siw' ), Properties::EMAIL, Properties::PHONE );
	}
}
