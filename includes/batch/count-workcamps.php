<?php

namespace SIW\Batch;

/**
 * Proces om aantal zichtbare groepsprojecten per term te tellen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Count_Workcamps extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'count_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'tellen groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer alle terms van de relevante taxonomieën
	 *
	 * @return array
	 */
	protected function select_data() {
		$taxonomies = [
			'product_cat',
			'pa_land',
			'pa_maand',
		];
		
		$data = [];
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( $taxonomy, [ 'hide_empty' => true ] );
			foreach ( $terms as $term ) {
				$data[] = [ 'taxonomy' => $taxonomy, 'term_slug' => $term->slug, 'term_id'=> $term->term_id ];
	
			}
		}
		return $data;
	}

	/**
	 * Tel het aantal projecten van de term
	 *
	 * @param mixed $term
	 *
	 * @return bool
	 */
	protected function task( $term ) {

		$tax_query = [
			[
				'taxonomy' => $term['taxonomy'],
				'field'    => 'slug',
				'terms'    => $term['term_slug'],
			],
		];
	
		$products = wc_get_products(
			[
				'status'     => 'publish',
				'limit'      => -1,
				'return'     => 'ids',
				'visibility' => 'visible',
				'tax_query'  => $tax_query,
			]
		);
		$count = count( $products );
		update_term_meta( $term['term_id'], 'project_count', $count ); 
		$this->increment_processed_count();

		return false;
	}
}
