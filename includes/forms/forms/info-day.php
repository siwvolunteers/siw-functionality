<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Content\Posts\Events;
use SIW\Data\Continent;
use SIW\Data\Mailjet\Property;
use SIW\Data\Project_Type;
use SIW\Interfaces\Forms\Confirmation_Mail as I_Confirmation_Mail;
use SIW\Interfaces\Forms\Export_To_Mailjet as I_Export_To_Mailjet;
use SIW\Interfaces\Forms\Form as I_Form;
use SIW\Interfaces\Forms\Notification_Mail as I_Notification_Mail;

class Info_Day implements I_Form, I_Confirmation_Mail, I_Notification_Mail, I_Export_To_Mailjet {

	public const FORM_ID = 'info_day';

	#[\Override]
	public function get_form_id(): string {
		return self::FORM_ID;
	}

	#[\Override]
	public function get_form_name(): string {
		return __( 'Aanmelding infodag', 'siw' );
	}

	#[\Override]
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
				'options'  => Project_Type::list(),
			],
			[
				'id'       => 'destination',
				'type'     => 'checkbox_list',
				'name'     => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
				'required' => false,
				'options'  => Continent::list(),
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

	#[\Override]
	public function get_notification_mail_subject(): string {
		return 'Aanmelding Infodag {{ info_day_date }}';
	}

	#[\Override]
	public function get_notification_mail_message(): string {
		return 'Via de website is onderstaande aanmelding voor de Infodag van {{ info_day_date }} binnengekomen:';
	}

	#[\Override]
	public function get_confirmation_mail_subject(): string {
		// translators: %s is de datum van de infodag
		return sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '{{ info_day_date }}' );
	}

	#[\Override]
	public function get_confirmation_mail_message(): string {
		// translators: %s is de voornaam van de klant
		return sprintf( __( 'Beste %s,', 'siw' ), '{{ first_name }}' ) . BR2 .
		// translators: %s is de datum van de infodag
		sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '{{ info_day_date }}' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'Een paar dagen van tevoren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
		__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' );
	}

	protected function get_info_days(): array {
		$upcoming_info_days = Events::get_future_info_days( [ 'number' => -1 ] );

		// Fallback voor als er nog geen infodagen bekend zijn
		if ( empty( $upcoming_info_days ) ) {
			return [ '-1' => __( 'Nog niet bekend', 'siw' ) ];
		}

		foreach ( $upcoming_info_days as $info_day ) {
			$date = wp_date( 'j F', $info_day->get_event_date()->getTimestamp() );
			$info_days[ $info_day->get_id() ] = $info_day->is_online() ? sprintf( '%s (%s)', $date, __( 'online', 'siw' ) ) : $date;
		}

		return $info_days;
	}

	protected function get_age_ranges(): array {
		return [
			'16-25',
			'26-30',
			'31-50',
			'50+',
		];
	}

	protected function get_referral_options(): array {
		return [
			'google'    => __( 'Via Google', 'siw' ),
			'facebook'  => __( 'Via Facebook', 'siw' ),
			'instagram' => __( 'Via Instagram', 'siw' ),
			'fair'      => __( 'Via een beurs', 'siw' ),
			'other'     => __( 'Via iemand anders', 'siw' ),
		];
	}

	#[\Override]
	public function get_mailjet_list_id( \WP_REST_Request $request ): int {
		$event_post_id = $request->get_param( 'info_day_date' );
		//TODO: fallback mailjet list voor onbekende infodag
		return (int) siw_meta( 'mailjet_list_id', [], $event_post_id );
	}

	#[\Override]
	public function get_mailjet_properties( \WP_REST_Request $request ): array {
		return [
			Property::FIRST_NAME->value            => $request->get_param( 'first_name' ),
			Property::LAST_NAME->value             => $request->get_param( 'last_name' ),
			Property::AGE_RANGE->value             => $this->get_age_ranges()[ $request->get_param( 'age' ) ],
			Property::INTEREST_DESTINATION->value  => implode( ', ', array_map( fn( string $value ): string => Continent::tryFrom( $value )?->label() ?? '', $request->get_param( 'destination' ) ?? [] ) ),
			Property::INTEREST_PROJECT_TYPE->value => implode( ', ', array_map( fn( string $value ): string => Project_Type::tryFrom( $value )?->label() ?? '', $request->get_param( 'project_type' ) ?? [] ) ),
			Property::REFERRAL->value              => $this->get_referral_options()[ $request->get_param( 'referral' ) ] . SPACE . $request->get_param( 'referral_other' ),
		];
	}
}
