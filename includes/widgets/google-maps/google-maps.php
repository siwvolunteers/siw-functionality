<?php

/**
 * Widget met Google Maps kaart
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Element_Google_Maps
 * 
 * @widget_data
 * Widget Name: SIW: Google Maps
 * Description: Toont Google Maps kaart
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Widget_Google_Maps extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='google_maps';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'location';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Google Maps', 'siw');
		$this->widget_description = __( 'Toont Google Maps kaart', 'siw' );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {

		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
			],
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 5,
				'default_editor' => 'html',
			],
			'zoom' => [
				'type'    => 'slider',
				'label'   => __( 'Zoomniveau', 'siw' ),
				'default' => 10,
				'min'     => 1,
				'max'     => 20,
				'integer' => true
			],
			'markers' => [
				'type'       => 'repeater',
				'label'      => __( 'Markers' , 'siw' ),
				'item_name'  => __( 'Marker', 'siw' ),
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
					'description' => [
						'type'           => 'tinymce',
						'label'          => __( 'Beschrijving', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
					],
					'location' => [
						'type'    => 'radio',
						'label'   => __( 'Locatie', 'siw' ),
						'default' => 'address',
						'options' => [
							'address'     => __( 'Adres', 'siw' ),
							'coordinates' => __( 'Coördinaten', 'siw' ),
						],
						'state_emitter' => [
							'callback'  => 'select',
							'args'      => [ 'location_{$repeater}' ],
						],
					],
					'address' => [
						'type'          => 'textarea',
						'label'         => __( 'Adres', 'siw' ),
						'rows'          => 4,
						'state_handler' => [
							'location_{$repeater}[address]' => ['show'],
							'_else[location_{$repeater}]'   => ['hide'],
						],

					],
					'lat' => [
						'type'          => 'number',
						'label'         => __( 'Latitude', 'siw' ),
						'state_handler' => [
							'location_{$repeater}[coordinates]' => ['show'],
							'_else[location_{$repeater}]'       => ['hide'],
						],
					],
					'lng' => [
						'type'          => 'number',
						'label'         => __( 'Latitude', 'siw' ),
						'state_handler' => [
							'location_{$repeater}[coordinates]' => ['show'],
							'_else[location_{$repeater}]'       => ['hide'],
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
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		$output = '';
		if ( isset( $instance['intro'] ) ) {
			$output .= wpautop( wp_kses_post( $instance['intro'] ) );
		}
		$map = new SIW_Element_Google_Maps();
		
		$map->set_options( ['zoom' => $instance['zoom'] ] );

		foreach ( $instance['markers'] as $marker ) {
			if ( 'address' == $marker['location'] ) {
				$map->add_location_marker( $marker['address'], $marker['title'], $marker['description'] );
			}
			elseif ( 'coordinates' == $marker['location'] ) {
				$map->add_marker( $marker['lat'], $marker['lng'], $marker['title'], $marker['description'] );
			}
		}
		return $output . $map->generate();
	}

}