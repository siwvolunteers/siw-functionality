<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Accordion_Tabs as Accordion_Tabs_Element;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Accordion
 * Description: Toont accordion.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Accordion extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'accordion';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Accordion', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont accordion', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'list-view';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
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
					'show_button' => [
						'type'          => 'checkbox',
						'label'         => __( 'Toon een knop', 'siw' ),
						'default'       => false,
						'state_emitter' => [
							'callback' => 'conditional',
							'args'     => [
								'button_{$repeater}[show]: val',
								'button_{$repeater}[hide]: ! val',
							],
						],
					],
					'button_text' => [
						'type'          => 'text',
						'label'         => __( 'Knoptekst', 'siw' ),
						'state_handler' => [
							'button_{$repeater}[show]' => [ 'show' ],
							'button_{$repeater}[hide]' => [ 'hide' ],
						],
					],
					'button_url'  => [
						'type'          => 'text',
						'label'         => __( 'URL', 'siw' ),
						'sanitize'      => 'wp_make_link_relative',
						'description'   => __( 'Relatief', 'siw' ),
						'state_handler' => [
							'button_{$repeater}[show]' => [ 'show' ],
							'button_{$repeater}[hide]' => [ 'hide' ],
						],
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

	/** {@inheritDoc} */
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
