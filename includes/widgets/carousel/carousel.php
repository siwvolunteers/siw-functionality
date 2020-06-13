<?php

namespace SIW\Widgets;

use SIW\Elements\Carousel as Element_Carousel;
use SIW\HTML;
use SIW\Util\Links;

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
	protected $widget_id = 'carousel';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'format-gallery';

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
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) {

		$instance = $this->parse_instance( $instance );

		$carousel = new Element_Carousel();
		$carousel->set_post_type( $instance['post_type'] );
		$carousel->set_items( $instance['items'] );
		$carousel->set_columns( $instance['columns'] );
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
		
		//Content genereren
		$content = '';
		if ( ! empty( $instance['intro'] ) ) {
			$content .= HTML::div(
				['class' => 'carousel-intro'],
				wpautop( wp_kses_post( $instance['intro'] ) )
			);
		}
		$content .= $carousel->render();

		if ( $instance['show_button'] ) {
			$content .= HTML::div(
				['class' => 'carousel-button'],
				$this->generate_button( $instance['button_text'], $instance['post_type'], $instance['taxonomy'], $instance['term'] ),
			);
		}

		return $content;
	}

	/**
	 * Parse args voor instance
	 *
	 * @param array $instance
	 * @return array
	 */
	protected function parse_instance( $instance ) {
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
	 * Genereert knop
	 *
	 * @param string $button_text
	 * @param string $post_type
	 * @param string $taxonomy
	 * @param string $term
	 * @return string
	 */
	protected function generate_button( string $button_text, string $post_type, string $taxonomy, string $term ) {
		if ( ! empty( $taxonomy ) && ! empty( $term ) ) {
			$link = get_term_link( $term, $taxonomy );
		}
		else {
			$link = get_post_type_archive_link( $post_type );
		}
		return Links::generate_button_link( $link, $button_text );
	}

	/**
	 * Haalt ondersteunde post types op
	 * 
	 * @return array
	 */
	protected function get_post_types() {
		$post_types = [
			'product' => __( 'Groepsprojecten', 'siw' ), //TODO: verplaatsen naar Compat/WooCommerce
		];
		/**
		 * Custom post types
		 *
		 * @param array $post_types
		 */
		$post_types = apply_filters( 'siw_carousel_post_types', $post_types );

		return $post_types;
	}

	/**
	 * Haalt ondersteunde taxonomieën op
	 * 
	 * @return array
	 */
	protected function get_taxonomies() {
		$taxonomies = [] ;
		$taxonomies['product'] = [
			'product_cat'        => __( 'Continent', 'siw' ), //TODO: verplaatsen naar Compat/WooCommerce
		];
		/**
		 * Taxonomieën per post type
		 *
		 * @param array $taxonomies
		 */
		$taxonomies = apply_filters( 'siw_carousel_post_type_taxonomies', $taxonomies );

		return $taxonomies;
	}

	/**
	 * Haal optielijst van taxonomie op
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	protected function get_term_options( string $taxonomy ) {
		$terms = get_terms( $taxonomy );
		$term_options[''] = __( 'Alle', 'siw' );
		foreach ( $terms as $term ) {
			$term_options[ $term->slug ] = $term->name;
		}
		return $term_options;
	}
}
