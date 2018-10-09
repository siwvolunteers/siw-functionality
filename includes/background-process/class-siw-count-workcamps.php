<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om aantal zichtbare groepsprojecten per term te tellen
 * 
 * @package SIW\Background process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Count_Workcamps extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'count_workcamps_process';

	/**
	 * @var string
	 */
	protected $name = 'tellen groepsprojecten';


	/**
	 * Selecteer alle terms van de relevante taxonomieën
	 *
	 * @return array
	 */
	protected function select_data() {
		$taxonomies = array(
			'product_cat',
			'pa_land',
			'pa_maand',
		);
		
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( $taxonomy, array( 'hide_empty' => true ) );
			foreach ( $terms as $term ) {
				$data[] = array( 'taxonomy' => $taxonomy, 'term_slug' => $term->slug );
	
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

        $taxonomy = $item['taxonomy'];
        $term_slug = $item['term_slug']; 
		siw_count_projects_by_term( $taxonomy, $term_slug, true );
		$this->increment_processed_count();

		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Tellen projecten', 'siw' ) );
	siw_register_background_process( 'SIW_Count_Workcamps', 'count_workcamps', $node, $parent_nodes, true );
} );