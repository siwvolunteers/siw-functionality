<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Accordion_Tabs as Accordion_Tabs_Element;

/**
 * Widget Name: SIW: Accordion
 * Description: Toont accordion.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Accordion extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Accordion', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont accordion', 'siw' );
	}

	#[\Override]
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	#[\Override]
	protected function get_dashicon(): string {
		return 'list-view';
	}

	#[\Override]
	protected function supports_title(): bool {
		return true;
	}

	#[\Override]
	protected function supports_intro(): bool {
		return true;
	}

	#[\Override]
	protected function get_widget_fields(): array {
		$widget_fields = [
			'panes'        => [
				'type'       => 'repeater',
				'label'      => __( 'Accordeon', 'siw' ),
				'item_name'  => __( 'Paneel', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val',
				],
				'fields'     => [
					'title'       => [
						'type'  => 'text',
						'label' => __( 'Titel', 'siw' ),
					],
					'content'     => [
						'type'           => 'tinymce',
						'label'          => __( 'Inhoud', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
					],
				],
			],
			'tabs_allowed' => [
				'type'        => 'checkbox',
				'label'       => __( 'Tabs indien mogelijk', 'siw' ),
				'description' => __( 'Tabs op desktop, accordion op mobiel', 'siw' ),
				'default'     => false,
			],
		];
		return $widget_fields;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		if ( ! isset( $instance['panes'] ) || empty( $instance['panes'] ) ) {
			return [];
		}

		return [
			'content' => Accordion_Tabs_Element::create()
				->add_items( $instance['panes'] )
				->set_tabs_allowed( $instance['tabs_allowed'] )
				->generate(),
		];
	}
}
