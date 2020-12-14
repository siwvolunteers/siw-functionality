<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;

/**
 * Widget met infoboxes
 *
 * @copyright 2019-2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Infobox
 * Description: Toont infobox met tekst in icon
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Infobox extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id ='infobox';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'align-right';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Infobox', 'siw');
		$this->widget_description = __( 'Toont infoboxes met icon', 'siw' );
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
			'infoboxes' => [
				'type'       => 'repeater',
				'label'      => __( 'Infoboxes' , 'siw' ),
				'item_name'  => __( 'Infobox', 'siw' ),
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
				],
			],

		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ): array {
		return [
			'intro'     => $instance['intro'],
			'infoboxes' => Elements::generate_infoboxes( $instance['infoboxes'] ),
		];
	}
}
