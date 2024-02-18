<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Dashicons;
use SIW\Elements\Description_List;
use SIW\Properties;

/**
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Organisation extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Organisatiegegevens', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont organisatiegegevens', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::BUILDING;
	}

	#[\Override]
	protected function get_widget_fields(): array {
		$widget_form = [
			'renumeration_policy' => [
				'type'           => 'tinymce',
				'label'          => __( 'Beloningsbeleid', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
				'required'       => true,
			],
		];
		return $widget_form;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		$data = [
			[
				'term'        => __( 'Statutaire naam', 'siw' ),
				'description' => Properties::STATUTORY_NAME,
			],
			[
				'term'        => __( 'RSIN/fiscaal nummer', 'siw' ),
				'description' => Properties::RSIN,
			],
			[
				'term'        => __( 'KVK-nummer', 'siw' ),
				'description' => Properties::KVK,
			],
			[
				'term'        => __( 'Rekeningnummer', 'siw' ),
				'description' => Properties::IBAN,
			],
			[
				'term'        => __( 'Beloningsbeleid', 'siw' ),
				'description' => $instance['renumeration_policy'],
			],
		];

		return [
			'content' => Description_List::create()->add_items( $data )->generate(),
		];
	}
}
