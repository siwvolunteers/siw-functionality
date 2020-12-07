<?php declare(strict_types=1);

namespace SIW\Batch;

/**
 * Proces om terms bij te werken
 *
 * - Lege terms verwijderen
 * - Tellen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Terms extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_terms';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'bijwerken terms';

	/**
	 * {@inheritDoc}
	 */
	protected string $category = 'algemeen';

	/**
	 * Is de term bijgewerkt?
	 */
	protected bool $updated;

	/**
	 * Selecteer de terms van de relevante taxonomieën
	 *
	 * @return array
	 */
	protected function select_data() : array {

		//TODO: verplaatsen naar woocommerce-compat
		$taxonomies = [ 
			'product_cat' => [
				'query_type' => 'products',
			],
			'pa_maand' => [
				'query_type'   => 'products',
				'delete_empty' => true,
			],
		];

		/**
		 * Taxomieën waarvan de terms bijgewerkt worden
		 *
		 * @param array $taxonomies
		 */
		$taxonomies = apply_filters( 'siw_update_terms_taxonomies', $taxonomies );
		
		foreach ( $taxonomies as $taxonomy => $args ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$args = wp_parse_args(
				$args,
				[
					'query_type'   => 'posts',
					'count'        => true,
					'delete_empty' => false,
					'meta_query'   => [],
				]
			);

			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );
			foreach ( $terms as $term ) {
				$data[] = [
					'taxonomy'  => $taxonomy,
					'term_slug' => $term->slug,
					'args'      => $args
				];
			}
		}
		return $data;
	}

	/**
	 * Bijwerken term
	 *
	 * @param array $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		$taxonomy = $item['taxonomy'];
		$term_slug = $item['term_slug'];
		$args = $item['args'];

		$term = get_term_by( 'slug', $term_slug, $taxonomy );

		if ( ! is_a( $term, 'WP_Term') ) {
			return false;
		}

		$this->updated = false;

		//Taxonomy subquery
		$tax_query = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			],
		];

		if ( 'products' == $args[ 'query_type'] ) {
			$visible_posts = wc_get_products(
				[
					'limit'      => -1,
					'return'     => 'ids',
					'tax_query'  => $tax_query,
					'visibility' => 'visible',
				]
			);
			$posts = wc_get_products(
				[
					'limit'      => -1,
					'return'     => 'ids',
					'tax_query'  => $tax_query,
				]
			);
		}
		else {
			$visible_posts = get_posts(
				[
					'post_type'  => 'any',
					'tax_query'  => $tax_query,
					'meta_query' => $args['meta_query'],
					'limit'      => -1,
					'return'     => 'ids',
				]
			);
			$posts = get_posts(
				[
					'post_type'  => 'any',
					'tax_query'  => $tax_query,
					'limit'      => -1,
					'return'     => 'ids',
				]
			);
		}
		$count = count( $posts );
		$visible_count = count( $visible_posts );

		//Lege terms eventueel weggooien
		if ( $args['delete_empty'] && 0 === $count ) {
			wp_delete_term( $term->term_id, $taxonomy );
			$this->increment_processed_count();
			return false;
		}

		if ( $args['count'] ) {
			$current_count = intval( get_term_meta( $term->term_id, 'post_count', true ) );

			if ( $current_count !== $visible_count ) {
				update_term_meta( $term->term_id, 'post_count', $visible_count );
				$this->updated = true;
			}
		}
		
		if ( $this->updated ) {
			$this->increment_processed_count();
		}
		return false;
	}
}
