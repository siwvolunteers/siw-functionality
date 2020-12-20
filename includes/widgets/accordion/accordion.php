<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Accordion
 * Description: Toont accordion.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Accordion extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id ='accordion';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'list-view';
	
	/**
	 * {@inheritDoc}
	 */
	protected bool $use_default_template = true;

	/**
	 * {@inheritDoc}
	 */	
	protected function set_widget_properties() {
		$this->widget_name = __( 'Accordion', 'siw');
		$this->widget_description = __( 'Toont accordion', 'siw' );
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
				'label'      => __( 'Accordeon' , 'siw' ),
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
	function get_template_variables( $instance, $args ) {
		return [
			'content' => Elements::generate_accordion( $instance['panes'] )
		];
	}
}
