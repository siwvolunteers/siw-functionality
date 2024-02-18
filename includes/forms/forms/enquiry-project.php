<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Forms\Form;

class Enquiry_Project extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Infoverzoek Groepsproject', 'siw' );
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
				'id'       => 'phone',
				'type'     => 'tel',
				'name'     => __( 'Telefoonnummer', 'siw' ),
				'required' => false,
			],
			[
				'id'      => 'question',
				'type'    => 'textarea',
				'name'    => __( 'Vraag', 'siw' ),
				'columns' => Form::FULL_WIDTH,
			],
		];
	}
}
