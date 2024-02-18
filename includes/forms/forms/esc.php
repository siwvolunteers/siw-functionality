<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Forms\Form;

class ESC extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmelding ESC', 'siw' );
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
				'name' => __( 'Waarom wil je graag aan een ESC-project deelnemen?', 'siw' ),
			],
			[
				'id'   => 'period',
				'type' => 'textarea',
				'name' => __( 'In welke periode zou je graag een ESC project willen doen?', 'siw' ),
			],
		];
	}
}
