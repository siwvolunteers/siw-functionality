<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;

class ESC implements Form_Interface {
	
	/** {@inheritDoc} */
	public function get_id(): string {
		return 'esc';
	}
	
	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'ESC', 'siw' );
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
				'slug'  => 'achternaam',
				'type'  => 'text',
				'label' => __( 'Achternaam', 'siw' ),
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
				'slug'   => 'geslacht',
				'type'   => 'radio',
				'label'  => __( 'Geslacht', 'siw' ),
				'config' => [
					'inline' => true,
					'option' => siw_get_genders(),
				]
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
				'slug'   => 'postcode',
				'type'   => 'text',
				'label'  => __( 'Postcode', 'siw' ),
				'config' => [
					'postcode_lookup' => 'postcode',
					'placeholder'     => '1234 AB',
					'validation'      => 'postcode',
				],
			],
			[
				'slug'   => 'huisnummer',
				'type'   => 'text',
				'label'  => __( 'Huisnummer', 'siw' ),
				'config' => [
					'postcode_lookup' => 'housenumber',
				],
			],
			[
				'slug'   => 'straat',
				'type'   => 'text',
				'label'  => __( 'Straat', 'siw' ),
				'config' => [
					'postcode_lookup' => 'street',
				],
			],
			[
				'slug'   => 'woonplaats',
				'type'   => 'text',
				'label'  => __( 'Woonplaats', 'siw' ),
				'config' => [
					'postcode_lookup' => 'city',
				],
			],
			[
				'slug'   => 'motivatie',
				'type'   => 'paragraph',
				'label'  => __( 'Waarom wil je graag aan een ESC-project deelnemen?', 'siw' ),
				'config' => [
					'rows' => 7,
				]
			],
			[
				'slug'   => 'periode',
				'type'   => 'paragraph',
				'label'  => __( 'In welke periode zou je graag een ESC project willen doen?', 'siw' ),
				'config' => [
					'rows' => 7,
				],
			],
			[
				'slug'   => 'cv',
				'type'   => 'file',
				'label'  => __( 'Upload hier je CV (optioneel)', 'siw' ),
				'config' => [
					'attach'     => true,
					'media_lib'  => false,
					'allowed'    => 'pdf,docx',
					'max_upload' => wp_max_upload_size(),
				],
				'required' => false,
			],
		];
	}

	/** {@inheritDoc} */
	public function get_notification_subject(): string {
		return 'Aanmelding ESC';

	}

	/** {@inheritDoc} */
	public function get_notification_message(): string {
		return 'Via de website is onderstaande ESC-aanmelding binnengekomen:';
	}

	/** {@inheritDoc} */
	public function get_autoresponder_subject(): string {
		return __( 'Bevestiging aanmelding ESC', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_autoresponder_message(): string {
		return sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor je ESC-aanmelding.', 'siw' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'We nemen zo snel mogelijk contact met je op om in een gesprek verder met je kennis te maken en op zoek te gaan naar een leuk en geschikt project!', 'siw' );
	}
}
