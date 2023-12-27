<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Compatibility\WooCommerce;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Carousel as Element_Carousel;
use SIW\Util\Carousel as Carousel_Util;

/**
 * Widget met carousel
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Carousel
 * Description: Toont carousel.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Carousel extends Widget {

	/** Default aantal kolommen */
	private const DEFAULT_NUMBER_OF_COLUMNS = 4;

	/** Default aantal items */
	private const DEFAULT_NUMBER_OF_ITEMS = 6;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'carousel';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Carousel', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont carousel', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return self::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'format-gallery';
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
			'items'     => [
				'type'    => 'slider',
				'label'   => __( 'Aantal posts in carousel', 'siw' ),
				'default' => self::DEFAULT_NUMBER_OF_ITEMS,
				'min'     => 2,
				'max'     => 10,
			],
			'columns'   => [
				'type'    => 'slider',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'default' => self::DEFAULT_NUMBER_OF_COLUMNS,
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
					'args'     => [ 'post_type' ],
				],
			],
		];

		$post_types = array_keys( $this->get_post_types() );

		foreach ( $post_types as $post_type ) {
			/** @var \WP_Taxonomy[] */
			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			$post_type_taxonomies = wp_list_filter( $post_type_taxonomies, [ 'show_ui' => true ] );

			$post_type_taxonomy_fields = [];
			foreach ( $post_type_taxonomies as $taxonomy ) {

				$post_type_taxonomy_fields[ $taxonomy->name ] = [
					'type'    => 'checkboxes',
					'label'   => $taxonomy->label,
					'default' => '',
					'options' => $this->get_taxonomy_terms( $taxonomy ),
				];
			}

			if ( WooCommerce::PRODUCT_POST_TYPE === $post_type ) {
				$post_type_taxonomy_fields['show_featured_products'] = [
					'type'        => 'checkbox',
					'label'       => __( 'Aanbevolen projecten', 'siw' ),
					'description' => __( 'Toon alleen aanbevolen projecten', 'siw' ),
					'default'     => true,
				];
			}

			$widget_form[ "{$post_type}_filter" ] = [
				'type'          => 'section',
				'label'         => __( 'Filters', 'siw' ),
				'hide'          => false,
				'state_handler' => [
					"post_type[{$post_type}]" => [ 'show' ],
					'_else[post_type]'        => [ 'hide' ],
				],
				'fields'        => $post_type_taxonomy_fields,
			];
		}
		return $widget_form;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		if ( ! isset( $instance['post_type'] ) || empty( $instance['post_type'] ) ) {
			return [];
		}

		$posts = $this->get_slides( $instance );

		$carousel = Element_Carousel::create()
			->add_items( $posts )
			->set_columns( (int) $instance['columns'] );

		return [
			'content' => $carousel->generate(),
		];
	}

	protected function get_slides( array $instance ): array {

		$args = [];

		foreach ( $instance[ "{$instance['post_type']}_filter" ] as $taxonomy => $terms ) {

			if ( 'so_field_container_state' === $taxonomy ) {
				continue;
			}

			if ( WooCommerce::PRODUCT_POST_TYPE === $instance['post_type'] && 'show_featured_products' === $taxonomy && true === $terms ) {
				$args['featured'] = true;
			} elseif ( ! empty( $terms ) ) {
				$args['tax_query'][] = [
					'taxonomy' => $taxonomy,
					'terms'    => $terms,
					'field'    => 'slug',
					'operator' => 'IN',
				];
			}
		}

		if ( WooCommerce::PRODUCT_POST_TYPE === $instance['post_type'] ) {
			$args['limit'] = $instance['items'];
			$args['orderby'] = 'rand';
			$args['visibility'] = 'visible';

			$posts = array_map(
				[ Carousel_Util::class, 'product_to_carousel_slide' ],
				siw_get_products( $args )
			);
		} else {
			$args['post_type'] = $instance['post_type'];
			$args['posts_per_page'] = $instance['items'];

			$posts = array_map(
				[ Carousel_Util::class, 'post_to_carousel_slide' ],
				get_posts( $args )
			);
		}

		return $posts;
	}


	/** Haalt ondersteunde post types op */
	protected function get_post_types(): array {
		$post_types = array_map(
			fn( string $post_type ): \WP_Post_Type => get_post_type_object( $post_type ),
			get_post_types_by_support( Post_Type_Support::CAROUSEL->value )
		);

		return wp_list_pluck(
			$post_types,
			'label',
			'name',
		);
	}

	/** Haal optielijst van taxonomie op */
	protected function get_taxonomy_terms( \WP_Taxonomy $taxonomy ): array {
		$term_options = [];
		$terms = get_terms( $taxonomy->name );
		foreach ( $terms as $term ) {
			$term_options[ $term->slug ] = $term->name;
		}
		return $term_options;
	}
}
