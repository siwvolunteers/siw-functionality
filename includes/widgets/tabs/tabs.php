<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Tablist;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Tabs
 * Description: Toont tabs.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Tabs extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'tabs';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Tabs', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont tabs', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return 'default';
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'index-card';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw' ),
			],
			'panes' => [
				'type'       => 'repeater',
				'label'      => __( 'Tab' , 'siw' ),
				'item_name'  => __( 'Paneel', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val'
				],
				'fields' => [
					'title' => [
						'type'  => 'text',
						'label' => __( 'Titel', 'siw' )
					],
					'content' => [
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
							'callback'    => 'conditional',
							'args'        => [
								'button_{$repeater}[show]: val',
								'button_{$repeater}[hide]: ! val'
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
					'button_url' => [
						'type'          => 'text',
						'label'         => __( 'URL', 'siw' ),
						'description'   => __( 'Relatief', 'siw' ),
						'state_handler' => [
							'button_{$repeater}[show]' => [ 'show' ],
							'button_{$repeater}[hide]' => [ 'hide' ],
						],
					],
				]
			]
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		return [
			'content' => Tablist::create()->add_items( $instance['panes'] )->generate(),
		];
	}
}
