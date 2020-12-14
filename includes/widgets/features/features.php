<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;

/**
 * Widget met features
 *
 * @copyright 2019-2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Features
 * Description: Toont features met toelichting en link
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Features extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id ='features';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'yes';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Features', 'siw');
		$this->widget_description = __( 'Toont features met toelichting en link', 'siw' );
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
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
			'columns' => [
				'type'   => 'radio',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'options' => [
					1 => __( 'Eén', 'siw'),
					2 => __( 'Twee', 'siw' ),
					3 => __( 'Drie', 'siw' ),
					4 => __( 'Vier', 'siw' ),
				],
			],
			'features' => [
				'type'       => 'repeater',
				'label'      => __( 'Features' , 'siw' ),
				'item_name'  => __( 'Feature', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val'
				],
				'fields' => [
					'icon' => [
						'type'  => 'icon',
						'label' => __( 'Icoon', 'siw' ),
					],
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
					'add_link' => [
						'type'          => 'checkbox',
						'label'         => __( 'Voeg link toe', 'siw' ),
						'default'       => false,
						'state_emitter' => [
							'callback'    => 'conditional',
							'args'        => [
								'link_{$repeater}[show]: val',
								'link_{$repeater}[hide]: ! val'
							],
						],
					],
					'link_url' => [
						'type'          => 'text',
						'label'         => __( 'URL', 'siw' ),
						'state_handler' => [
							'link_{$repeater}[show]' => [ 'show' ],
							'link_{$repeater}[hide]' => [ 'hide' ],
						],
					],
				],
			],

		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_template_parameters(array $instance, array $args, array $template_vars, string $css_name): array {
		return[
			'intro' => $instance['intro'],
			'features' => Elements::generate_features( $instance['features'], (int) $instance['columns'] ),
		];
	}
}
