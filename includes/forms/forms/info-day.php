<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Content\Post\Event;
use SIW\Content\Posts\Events;
use SIW\Data\Continent;
use SIW\Data\Mailjet\Property;
use SIW\Data\Project_Type;
use SIW\Forms\Form;

class Info_Day extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmelding infodag', 'siw' );
	}

	#[\Override]
	protected function should_export_to_mailjet(): bool {
		return true;
	}

	#[\Override]
	public function get_fields(): array {
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
				'id'   => 'phone',
				'type' => 'tel',
				'name' => __( 'Telefoonnummer', 'siw' ),
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
	protected function get_notification_mail_subject( \WP_REST_Request $request ): string {
		$event_post_id = (int) $request->get_param( 'info_day_date' );
		if ( $event_post_id < 0 ) {
			return 0;
		}
		$event_post = new Event( $event_post_id );

		return sprintf(
			// translators: %s is de datum van de infodag
			__( 'Aanmelding Infodag %s', 'siw' ),
			wp_date( 'j F', $event_post->get_event_date()->getTimestamp(), wp_timezone() )
		);
	}

	#[\Override]
	protected function get_confirmation_mail_subject( \WP_REST_Request $request ): string {
		$event_post_id = (int) $request->get_param( 'info_day_date' );
		if ( $event_post_id < 0 ) {
			return 0;
		}
		$event_post = new Event( $event_post_id );

		return sprintf(
			// translators: %s is de datum van de infodag
			__( 'Bevestiging aanmelding Infodag %s', 'siw' ),
			wp_date( 'j F', $event_post->get_event_date()->getTimestamp(), wp_timezone() )
		);
	}

	protected function get_info_days(): array {
		$upcoming_info_days = Events::get_future_info_days( [ 'number' => -1 ] );

		// Fallback voor als er nog geen infodagen bekend zijn
		if ( empty( $upcoming_info_days ) ) {
			return [ '-1' => __( 'Nog niet bekend', 'siw' ) ];
		}

		foreach ( $upcoming_info_days as $info_day ) {
			$date = wp_date( 'j F', $info_day->get_event_date()->getTimestamp(), wp_timezone() );
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
		$event_post_id = (int) $request->get_param( 'info_day_date' );
		if ( $event_post_id < 0 ) {
			return 0; //TODO: fallback mailjet list voor onbekende infodag
		}
		$event_post = new Event( $event_post_id );

		return $event_post->get_mailjet_list_id();
	}

	#[\Override]
	public function get_mailjet_properties( \WP_REST_Request $request ): array {
		return [
			Property::FIRST_NAME->value            => $request->get_param( 'first_name' ),
			Property::LAST_NAME->value             => $request->get_param( 'last_name' ),
			Property::AGE_RANGE->value             => $this->get_age_ranges()[ $request->get_param( 'age' ) ],
			Property::INTEREST_DESTINATION->value  => implode( ', ', array_map( fn( string $value ): string => Continent::tryFrom( $value )?->label() ?? '', $request->get_param( 'destination' ) ?? [] ) ),
			Property::INTEREST_PROJECT_TYPE->value => implode( ', ', array_map( fn( string $value ): string => Project_Type::tryFrom( $value )?->label() ?? '', $request->get_param( 'project_type' ) ?? [] ) ),
			Property::REFERRAL->value              => sprintf( '%s %s', $this->get_referral_options()[ $request->get_param( 'referral' ) ], $request->get_param( 'referral_other' ) ),
		];
	}
}
