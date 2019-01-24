<?php
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
 * 
 * Widget Name: SIW: Accordion
 * Description: Toont accordion.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Accordion_Widget extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id ='accordion';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'list-view';

	/**
	 * {@inheritDoc}
	 */
	function __construct() {
		$this->widget_name = __( 'Accordion', 'siw');
		$this->widget_description = __( 'Toont accordion', 'siw' );
		$this->widget_fields = [
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
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		$content = SIW_Formatting::generate_accordion( $instance['panes']);
		return $content;
	}
}