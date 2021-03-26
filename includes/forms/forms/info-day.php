<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Aanmeldformulier infodag
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Info_Day implements Form_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'infodag';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Aanmeldformulier voor de Infodag', 'siw' );
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
				'slug'   => 'datum',
				'type'   => 'radio',
				'label'  => __( 'Naar welke Infodag wil je komen?', 'siw' ),
				'config' => [
					'inline' => false,
					'option' => $this->get_info_days()
				]
			],
			[
				'slug'     => 'soort_project',
				'type'     => 'checkbox',
				'label'    => __( 'Heb je interesse in een bepaald soort project?', 'siw' ),
				'required' => false,
				'config'   => [
					'option' => \siw_get_project_types(),
				],
			],
			[
				'slug'     => 'bestemming',
				'type'     => 'checkbox',
				'label'    => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
				'required' => false,
				'config'   => [
					'option' => \siw_get_continents_list(),
				],
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Aanmelding Infodag %datum:label%';
	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is onderstaande aanmelding voor de Infodag van %datum:label% binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '%datum:label%' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '%datum:label%' )  . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'Uiterlijk één week van te voren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
		__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_info_days() : array {
		$info_days = siw_get_upcoming_info_days( 3 );
	
		//Fallback voor als er nog geen infodagen bekend zijn
		if ( empty( $info_days ) ) {
			return [ __( 'Nog niet bekend', 'siw' ) ];
		}

		$callback = function( &$value, $key ) {
			$date = siw_meta( 'event_date', [], $value );
			$value = siw_format_date( $date, false );
		};
		array_walk( $info_days, $callback );
		return $info_days;
	}
}
