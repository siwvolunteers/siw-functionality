<?php

namespace SIW\Widgets;

use SIW\Formatting;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Tabs
 * Description: Toont tabs.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Tabs extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='tabs';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'index-card';

	/**
	 * {@inheritDoc}
	 */	
	protected function set_widget_properties() {
		$this->widget_name = __( 'Tabs', 'siw');
		$this->widget_description = __( 'Toont tabs', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw'),
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

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		$content = Formatting::generate_tabs( $instance['panes'] );
		return $content;
	}
}
