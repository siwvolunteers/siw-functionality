<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Carousel as Element_Carousel;

/**
 * Widget met carousel
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Carousel
 * Description: Toont carousel.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Carousel extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id = 'carousel';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'format-gallery';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Carousel', 'siw' );
		$this->widget_description = __( 'Toont carousel', 'siw' );
	}

	/**
	 * Instantie van Carousel
	 *
	 * @var Element_Carousel
	 */
	protected $carousel;

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw' ),
			],
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 4,
				'default_editor' => 'html',
			],
			'items' => [
				'type'    => 'slider',
				'label'   => __( 'Aantal posts in carousel', 'siw' ),
				'default' => 6,
				'min'     => 2,
				'max'     => 10,
			],
			'columns' => [
				'type'    => 'slider',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'default' => 4,
				'min'     => 1,
				'max'     => 4,
			],
			'post_type' => [
				'type'          => 'select',
				'label'         => __( 'Type', 'siw' ),
				'prompt'        => __( 'Kies een type', 'siw' ),
				'options'       => $this->get_post_types(),
				'state_emitter' => [
					'callback' => 'select',
					'args'     => ['post_type'],
				],
			],
		];
		foreach ( $this->get_taxonomies() as $post_type => $taxonomies ) {
			$taxonomy_options = [
				'' => __( 'Geen', 'siw' ),
			];
			foreach ( $taxonomies as $taxonomy => $label ) {
				$taxonomy_options[ $taxonomy ] = $label;
			}

			$widget_form["{$post_type}_taxonomy"] = [
				'type'          => 'select',
				'label'         => __( 'Taxonomy', 'siw' ),
				'default'       => '',
				'options'       => $taxonomy_options,
				'state_handler' => [
					"post_type[{$post_type}]" => ['show'],
					'_else[post_type]'        => ['hide'],
				],
				'state_emitter' => [
					'callback' => 'select',
					'args'     => ["{$post_type}_taxonomy"],
				],
			];
			foreach ( $taxonomies as $taxonomy => $label ) {
				$widget_form[ $taxonomy ] = [
					'type'          => 'select',
					'label'         => $label,
					'default'       => '',
					'options'       => $this->get_term_options( $taxonomy ),
					'state_handler' => [
						"{$post_type}_taxonomy[{$taxonomy}]" => ['show'],
						"_else[{$post_type}_taxonomy]" => ['hide'],
						"post_type[{$post_type}]" => ['show'],
						'_else[post_type]'        => ['hide'],
					],
				];
			}
		}
		$widget_form['show_selected_products'] = [
			'type'          => 'checkbox',
			'label'         => __( 'Geselecteerde Groepsprojecten', 'siw' ),
			'description'   => __( 'Toon alleen voor de carousel geselecteerde Groepsprojecten', 'siw' ),
			'default'       => false,
			'state_handler' => [
				"post_type[product]" => ['show'],
				'_else[post_type]'   => ['hide'],
			],
		];
		$widget_form['show_button'] = [
			'type'          => 'checkbox',
			'label'         => __( 'Toon een knop', 'siw' ),
			'default'       => false,
			'state_emitter' => [
				'callback'    => 'conditional',
				'args'        => [
					'button[show]: val',
					'button[hide]: ! val'
				],
			],
		];
		$widget_form['button_text'] = [
			'type'          => 'text',
			'label'         => __( 'Knoptekst', 'siw' ),
			'state_handler' => [
				'button[show]' => [ 'show' ],
				'button[hide]' => [ 'hide' ],
			],
		];
	
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	function get_template_variables( $instance, $args ) {

		$instance = $this->parse_instance( $instance );

		$carousel = new Element_Carousel();
		$carousel->set_post_type( $instance['post_type'] );
		$carousel->set_items( intval( $instance['items'] ) );
		$carousel->set_columns( intval( $instance['columns'] ) );
		if ( ! empty( $instance['taxonomy'] ) && ! empty( $instance['term'] ) ) {
			$carousel->set_taxonomy_term( $instance['taxonomy'], $instance['term'] );
		}

		if ( 'product' == $instance['post_type'] && isset( $instance['show_selected_products'] ) && $instance['show_selected_products'] ) {
			$carousel->set_meta_query([
				'key'     => 'selected_for_carousel',
				'value'   => true,
				'compare' => '='
			]);
		}
		
		return [
			'intro'       => $instance['intro'] ?? null,
			'carousel'    => $carousel->render(),
			'show_button' => $instance['show_button'],
			'button'      => [
				'url'  => ( ! empty( $instance['taxonomy'] ) && ! empty( $instance['term'] ) ) ? get_term_link( $instance['term'], $instance['taxonomy'] ) : get_post_type_archive_link( $instance['post_type'] ),
				'text' => $instance['button_text'],
			],
		];
	}

	/**
	 * Parse args voor instance
	 *
	 * @param array $instance
	 * @return array
	 */
	protected function parse_instance( array $instance ) : array {
		$instance = wp_parse_args(
			$instance,
			[ 
				'post_type'   => '',
				'intro'       => '',
				'columns'     => 4,
				'items'       => 6,
				'show_button' => false,
				'button_text' => '',
			]
		);
		$instance['taxonomy'] = $instance["{$instance['post_type']}_taxonomy"] ?? '';
		$instance['term'] = $instance[ $instance['taxonomy'] ] ?? '';
		return $instance;
	}

	/**
	 * Haalt ondersteunde post types op
	 * 
	 * @return array
	 */
	protected function get_post_types() : array {
		return apply_filters( 'siw_carousel_post_types', [] );
	}

	/**
	 * Haalt ondersteunde taxonomieën op
	 * 
	 * @return array
	 */
	protected function get_taxonomies() : array {
		return apply_filters( 'siw_carousel_post_type_taxonomies', [] );
	}

	/**
	 * Haal optielijst van taxonomie op
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	protected function get_term_options( string $taxonomy ) : array {
		$terms = get_terms( $taxonomy );
		$term_options[''] = __( 'Alle', 'siw' );
		foreach ( $terms as $term ) {
			$term_options[ $term->slug ] = $term->name;
		}
		return $term_options;
	}
}
