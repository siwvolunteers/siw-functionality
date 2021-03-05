<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

/**
 * Bijwerken terms
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Update_Terms implements Batch_Action_Interface {

	/** Term meta voor aantal zichtbare posts */
	const POST_COUNT_TERM_META = 'post_count';

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_terms';
	}
	
	/** {@inheritDoc} */
	public function get_name(): string {
		return 'Bijwerken terms';
	}

	/** {@inheritDoc} */
	public function select_data() : array {

		//Filter om taxonomieën toe te voegen
		$taxonomies = apply_filters( 'siw_update_terms_taxonomies', [] );
		
		$data = get_terms( [
			'taxonomy'   => $taxonomies,
			'fields'     => 'tt_ids'
		]);

		if ( is_wp_error( $data ) ) {
			return [];
		}
		return $data;
	}

	/** {@inheritDoc} */
	public function process( $term_taxonomy_id ) {

	if ( ! is_int( $term_taxonomy_id ) ) {
			return;
		}

		$term = get_term_by( 'term_taxonomy_id', $term_taxonomy_id );
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return;
		}

		//Taxonomy subquery
		$tax_query = [
			[
				'taxonomy' => $term->taxonomy,
				'field'    => 'slug',
				'terms'    => $term->slug,
			],
		];
		
		//Filter om meta query voor actieve posts te zetten
		$meta_query = apply_filters( 'siw_update_terms_meta_query', [], $term->taxonomy );

		$posts = get_posts(
			[
				'post_type'  => 'any',
				'tax_query'  => $tax_query,
				'meta_query' => $meta_query,
				'limit'      => -1,
				'return'     => 'ids',
			]
		);

		$count = count( $posts );


		$current_count = intval( get_term_meta( $term->term_id, self::POST_COUNT_TERM_META, true ) );
		if ( $current_count !== $count ) {
			update_term_meta( $term->term_id, self::POST_COUNT_TERM_META, $count );
		}

		return;
	}
}
