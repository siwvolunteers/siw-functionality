<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Data\Continent;
use SIW\Forms\Form;

class Tailor_Made extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmelding Wereld-basis', 'siw' );
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

	protected function get_duration_options(): array {
		return [
			'1-3'  => __( '1-3 maanden', 'siw' ),
			'3-6'  => __( '4-6 maanden', 'siw' ),
			'7-12' => __( '7-12 maanden', 'siw' ),
		];
	}

	protected function get_destination_options(): array {
		$destinations = Continent::list();
		unset( $destinations['noord-amerika'] );
		unset( $destinations['europa'] );
		return $destinations;
	}
}
