<?php
/*
 * 
 * Widget Name: SIW: Accordion
 * Description: Toont accordion.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met contactinformatie
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 */
class SIW_Widget_Accordion extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='accordion';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'list-view';

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
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		$content = SIW_Formatting::generate_accordion( $instance['panes']);
		return $content;
	}
}