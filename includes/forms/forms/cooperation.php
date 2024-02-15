<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Forms\Form;

class Cooperation extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Interesse samenwerking', 'siw' );
	}

	#[\Override]
	public function get_fields(): array {
		return [
			[
				'id'   => 'organisation',
				'type' => 'text',
				'name' => __( 'Naam organisatie', 'siw' ),
			],
			[
				'id'   => 'contact_person',
				'type' => 'text',
				'name' => __( 'Naam contactpersoon', 'siw' ),
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
				'id'      => 'explanation',
				'type'    => 'textarea',
				'name'    => __( 'Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw' ),
				'columns' => Form::FULL_WIDTH,
			],
		];
	}
}
