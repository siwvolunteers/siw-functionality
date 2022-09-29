<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Google_Maps as Element_Google_Maps;

/**
 * Widget met Google Maps kaart
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Google Maps
 * Description: Toont Google Maps kaart
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Google_Maps extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'google_maps';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Google Maps', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont Google Maps kaart', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'location';
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

		$widget_form = [
			'zoom'    => [
				'type'    => 'slider',
				'label'   => __( 'Zoomniveau', 'siw' ),
				'default' => 10,
				'min'     => 1,
				'max'     => 20,
				'integer' => true,
			],
			'markers' => [
				'type'       => 'repeater',
				'label'      => __( 'Markers', 'siw' ),
				'item_name'  => __( 'Marker', 'siw' ),
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
					'description' => [
						'type'           => 'tinymce',
						'label'          => __( 'Beschrijving', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
					],
					'location'    => [
						'type'          => 'radio',
						'label'         => __( 'Locatie', 'siw' ),
						'default'       => 'address',
						'options'       => [
							'address'     => __( 'Adres', 'siw' ),
							'coordinates' => __( 'Coördinaten', 'siw' ),
						],
						'state_emitter' => [
							'callback' => 'select',
							'args'     => [ 'location_{$repeater}' ],
						],
					],
					'address'     => [
						'type'          => 'textarea',
						'label'         => __( 'Adres', 'siw' ),
						'rows'          => 4,
						'state_handler' => [
							'location_{$repeater}[address]' => [ 'show' ],
							'_else[location_{$repeater}]' => [ 'hide' ],
						],

					],
					'lat'         => [
						'type'          => 'number',
						'label'         => __( 'Latitude', 'siw' ),
						'state_handler' => [
							'location_{$repeater}[coordinates]' => [ 'show' ],
							'_else[location_{$repeater}]' => [ 'hide' ],
						],
					],
					'lng'         => [
						'type'          => 'number',
						'label'         => __( 'Latitude', 'siw' ),
						'state_handler' => [
							'location_{$repeater}[coordinates]' => [ 'show' ],
							'_else[location_{$repeater}]' => [ 'hide' ],
						],
					],
				],
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		$map = Element_Google_Maps::create()
			->set_zoom( (int) $instance['zoom'] );

		foreach ( $instance['markers'] as $marker ) {
			if ( 'address' === $marker['location'] ) {
				$map->add_location_marker( $marker['address'], $marker['title'], $marker['description'] );
			} elseif ( 'coordinates' === $marker['location'] ) {
				$map->add_marker( $marker['lat'], $marker['lng'], $marker['title'], $marker['description'] );
			}
		}

		return [
			'content' => $map->generate(),
		];
	}
}
