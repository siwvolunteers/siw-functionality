<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Confirmation_Mail as Confirmation_Mail_Interface;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Notification_Mail as Notification_Mail_Interface;

/**
 * Aanmelding infodag
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Info_Day implements Form_Interface, Confirmation_Mail_Interface, Notification_Mail_Interface {

	/** Aantal infodagen om te tonen */
	const NUMBER_OF_INFO_DAYS = 3;

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return 'info_day';
	}

	/** {@inheritDoc} */
	public function get_form_name(): string {
		return __( 'Aanmelding infodag', 'siw' );
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
				'id'    => 'age',
				'type'  => 'radio',
				'inline' => true,
				'name'   => __( 'In welke leeftijdscategorie val je?', 'siw' ),
				'options' => $this->get_age_ranges(),
			],
			[
				'id'      => 'info_day_date',
				'type'    => 'radio',
				'inline'  => false,
				'name'    => __( 'Naar welke Infodag wil je komen?', 'siw' ),
				'options' => $this->get_info_days()
			],
			[
				'id'       => 'project_type',
				'type'     => 'checkbox_list',
				'name'     => __( 'Heb je interesse in een bepaald soort project?', 'siw' ),
				'required' => false,
				'options'  => \siw_get_project_types(),
			],
			[
				'id'       => 'destination',
				'type'     => 'checkbox_list',
				'name'     => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
				'required' => false,
				'options'  => \siw_get_continents_list(),
			],
			[
				'id'      => 'referal',
				'type'    => 'radio',
				'name'    => __( 'Hoe ben je op de website van SIW gekomen?', 'siw' ),
				'options' => $this->get_referral_options(),
			],
			[
				'id'       => 'referel_other',
				'type'     => 'text',
				'name'     => __( 'Namelijk', 'siw' ),
				'required' => false, //TODO: conditioneel verplicht maken in REST API
				'visible'  => [ 'referal', 'other'],
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_mail_subject(): string {
		return 'Aanmelding Infodag {{ info_day_date }}';
	}

	/** {@inheritDoc} */
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaande aanmelding voor de Infodag van {{ info_day_date }} binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_subject(): string {
		return sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '{{ info_day_date }}' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '{{ info_day_date }}' )  . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'Uiterlijk één week van te voren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
		__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_info_days(): array {
		$upcoming_info_days = siw_get_upcoming_info_days( self::NUMBER_OF_INFO_DAYS );
	
		//Fallback voor als er nog geen infodagen bekend zijn
		if ( empty( $upcoming_info_days ) ) {
			return [ 'unknown' => __( 'Nog niet bekend', 'siw' ) ];
		}

		foreach ( $upcoming_info_days as $info_day ) {
			$date = siw_meta( 'event_date', [], $info_day );
			$info_days[ $date ] = siw_format_date( $date, false );
		}

		return $info_days;
	}

	/** Opties voor leeftijdsranges */
	protected function get_age_ranges(): array {
		return [
			'16-25',
			'26-30',
			'31-50',
			'50 en ouder',
		];
	}

	/** Opties voor referral */
	protected function get_referral_options(): array {
		return [
			'google'    => __('Via Google', 'siw' ),
			'facebook'  => __('Via Google', 'siw' ),
			'instagram' => __('Via Instagram', 'siw' ),
			'fair'      => __('Via een beurs', 'siw' ),
			'other'     => __( 'Via iemand anders', 'siw' ),
		];
	}
}
