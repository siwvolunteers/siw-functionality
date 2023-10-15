<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Data\Project_Type;
use SIW\Integrations\Mailjet;
use SIW\Interfaces\Forms\Confirmation_Mail as I_Confirmation_Mail;
use SIW\Interfaces\Forms\Export_To_Mailjet as I_Export_To_Mailjet;
use SIW\Interfaces\Forms\Form as I_Form;
use SIW\Interfaces\Forms\Notification_Mail as I_Notification_Mail;

/**
 * Aanmelding infodag
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Info_Day implements I_Form, I_Confirmation_Mail, I_Notification_Mail, I_Export_To_Mailjet {

	/** Formulier ID */
	public const FORM_ID = 'info_day';

	/** {@inheritDoc} */
	public function get_form_id(): string {
		return self::FORM_ID;
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
				'name' => __( 'E-mailadres', 'siw' ),
			],
			[
				'id'       => 'phone',
				'type'     => 'tel',
				'name'     => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
			],
			[
				'id'      => 'info_day_date',
				'type'    => 'radio',
				'inline'  => false,
				'name'    => __( 'Naar welke Infodag wil je komen?', 'siw' ),
				'options' => $this->get_info_days(),
			],
			[
				'id'       => 'project_type',
				'type'     => 'checkbox_list',
				'name'     => __( 'Heb je interesse in een bepaald soort project?', 'siw' ),
				'required' => false,
				'options'  => Project_Type::toArray(),
			],
			[
				'id'       => 'destination',
				'type'     => 'checkbox_list',
				'name'     => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
				'required' => false,
				'options'  => \siw_get_continents_list(),
			],
			[
				'id'      => 'age',
				'type'    => 'radio',
				'inline'  => false,
				'name'    => __( 'In welke leeftijdscategorie val je?', 'siw' ),
				'options' => $this->get_age_ranges(),
			],
			[
				'id'      => 'referral',
				'type'    => 'radio',
				'inline'  => false,
				'name'    => __( 'Hoe ben je op de website van SIW gekomen?', 'siw' ),
				'options' => $this->get_referral_options(),
			],
			[
				'id'       => 'referral_other',
				'type'     => 'text',
				'name'     => __( 'Namelijk', 'siw' ),
				'required' => false, // TODO: conditioneel verplicht maken in REST API
				'visible'  => [ 'referral', 'other' ],
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
		// translators: %s is de datum van de infodag
		return sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '{{ info_day_date }}' );
	}

	/** {@inheritDoc} */
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		// translators: %s is de datum van de infodag
		sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '{{ info_day_date }}' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'Een paar dagen van tevoren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
		__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_info_days(): array {
		$upcoming_info_days = siw_get_upcoming_info_days( -1 );

		// Fallback voor als er nog geen infodagen bekend zijn
		if ( empty( $upcoming_info_days ) ) {
			return [ 'unknown' => __( 'Nog niet bekend', 'siw' ) ];
		}

		foreach ( $upcoming_info_days as $post_id ) {
			$date = siw_meta( 'event_date', [], $post_id );
			$online = siw_meta( 'online', [], $post_id );
			$info_days[ $post_id ] = $online ? sprintf( '%s (%s)', siw_format_date( $date, false ), __( 'online', 'siw' ) ) : siw_format_date( $date, false );
		}

		return $info_days;
	}

	/** Opties voor leeftijdsranges */
	protected function get_age_ranges(): array {
		return [
			'16-25',
			'26-30',
			'31-50',
			'50+',
		];
	}

	/** Opties voor referral */
	protected function get_referral_options(): array {
		return [
			'google'    => __( 'Via Google', 'siw' ),
			'facebook'  => __( 'Via Facebook', 'siw' ),
			'instagram' => __( 'Via Instagram', 'siw' ),
			'fair'      => __( 'Via een beurs', 'siw' ),
			'other'     => __( 'Via iemand anders', 'siw' ),
		];
	}

	/** {@inheritDoc} */
	public function get_mailjet_list_id( \WP_REST_Request $request ): int {
		$event_post_id = $request->get_param( 'info_day_date' );
		return (int) siw_meta( 'mailjet_list_id', [], $event_post_id );
	}

	/** {@inheritDoc} */
	public function get_mailjet_properties( \WP_REST_Request $request ): array {
		return [
			Mailjet::PROPERTY_FIRST_NAME            => $request->get_param( 'first_name' ),
			Mailjet::PROPERTY_LAST_NAME             => $request->get_param( 'last_name' ),
			Mailjet::PROPERTY_AGE_RANGE             => $this->get_age_ranges()[ $request->get_param( 'age' ) ],
			Mailjet::PROPERTY_INTEREST_DESTINATION  => implode( ', ', array_map( fn( string $value ): string => \siw_get_continents_list()[ $value ], $request->get_param( 'destination' ) ?? [] ) ),
			Mailjet::PROPERTY_INTEREST_PROJECT_TYPE => implode( ', ', array_map( fn( string $value ): string => Project_Type::toArray()[ $value ], $request->get_param( 'project_type' ) ?? [] ) ),
			Mailjet::PROPERTY_REFERRAL              => $this->get_referral_options()[ $request->get_param( 'referral' ) ] . SPACE . $request->get_param( 'referral_other' ),
		];
	}
}
