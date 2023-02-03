<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Features as Features_Element;

/**
 * Widget met features
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Features
 * Description: Toont features met toelichting en link
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Features extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'features';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Features', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont features met toelichting en link', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'yes';
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
			'columns'  => [
				'type'    => 'radio',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'options' => [
					1 => __( 'Eén', 'siw' ),
					2 => __( 'Twee', 'siw' ),
					3 => __( 'Drie', 'siw' ),
					4 => __( 'Vier', 'siw' ),
				],
			],
			'features' => [
				'type'       => 'repeater',
				'label'      => __( 'Features', 'siw' ),
				'item_name'  => __( 'Feature', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val',
				],
				'fields'     => [
					'icon'     => [
						'type'  => 'icon',
						'label' => __( 'Icoon', 'siw' ),
					],
					'title'    => [
						'type'  => 'text',
						'label' => __( 'Titel', 'siw' ),
					],
					'content'  => [
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
							'callback' => 'conditional',
							'args'     => [
								'link_{$repeater}[show]: val',
								'link_{$repeater}[hide]: ! val',
							],
						],
					],
					'link_url' => [
						'type'          => 'text',
						'label'         => __( 'URL', 'siw' ),
						'sanitize'      => 'wp_make_link_relative',
						'description'   => __( 'Relatief', 'siw' ),
						'state_handler' => [
							'link_{$repeater}[show]' => [ 'show' ],
							'link_{$repeater}[hide]' => [ 'hide' ],
						],
					],
				],
			],

		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		if ( ! isset( $instance['features'] ) || empty( $instance['features'] ) ) {
			return [];
		}

		return [
			'content' => Features_Element::create()->add_items( $instance['features'] )->set_columns( (int) $instance['columns'] )->generate(),
		];
	}
}
