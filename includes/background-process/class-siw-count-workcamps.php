<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om aantal zichtbare groepsprojecten per term te tellen
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Count_Workcamps extends SIW_Background_Process {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'count_workcamps_process';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'tellen groepsprojecten';

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
	 * @param mixed $item
	 *
	 * @return bool
	 */
	protected function task( $item ) {

		$tax_query = [
			[
				'taxonomy' => $item['taxonomy'],
				'field'    => 'slug',
				'terms'    => $item['term_slug'],
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
		update_term_meta( $item['term_id'], 'project_count', $count ); 
		$this->increment_processed_count();

		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'workcamps' =>  [ 'title' => __( 'Groepsprojecten', 'siw' ) ]	];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Tellen projecten', 'siw' ) ];
	siw_register_background_process( 'SIW_Count_Workcamps', 'count_workcamps', $node, $parent_nodes, true );
} );
