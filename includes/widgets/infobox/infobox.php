<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Infoboxes;

/**
 * Widget met infoboxes
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Infobox
 * Description: Toont infobox met tekst in icon
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Infobox extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'infobox';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Infobox', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont infoboxes met icon', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'align-right';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw' ),
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

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		return [
			'intro'   => $instance['intro'],
			'content' => Infoboxes::create( $instance['infoboxes'] )->generate(),
		];
	}
}
