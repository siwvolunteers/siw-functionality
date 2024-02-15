<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Data\Country;
use SIW\Facades\WooCommerce;
use SIW\Forms\Form;
use SIW\WooCommerce\Product\WC_Product_Project;


class Leader_Dutch_Projects extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmelding projectbegeleider NP', 'siw' );
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
				'id'   => 'date_of_birth',
				'type' => 'date',
				'name' => __( 'Geboortedatum', 'siw' ),
			],
			[
				'id'      => 'project_preference',
				'type'    => 'checkbox_list',
				'name'    => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
				'options' => $this->get_project_options(),
			],
			[
				'id'      => 'motivation',
				'type'    => 'textarea',
				'name'    => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
				'rows'    => 7,
				'columns' => Form::FULL_WIDTH,
			],
		];
	}

	protected function get_project_options(): array {
		$project_options['none'] = __( 'Geen voorkeur', 'siw' );

		$args = [
			'country' => Country::NETHERLANDS->value,
		];
		$projects = array_filter(
			WooCommerce::get_products( $args ),
			fn( WC_Product_Project $project ): bool => ! $project->is_hidden()
		);

		usort( $projects, fn( WC_Product_Project $project_1, WC_Product_Project $project_2 ) => strcmp( $project_1->get_sku(), $project_2->get_sku() ) );

		foreach ( $projects as $project ) {
			$project_options[ sanitize_title( $project->get_sku() ) ] = $project->get_formatted_name();
		}
		return $project_options;
	}
}
