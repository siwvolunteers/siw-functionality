<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Update_Taxonomies extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'update_taxonomies_process';

	/**
	 * Naam
	 *
	 * @var string
	 */
	protected $name = 'bijwerken taxonomies';

	/**
	 * 
	 *
	 * @return array
	 */
	protected function select_data() {

		$taxonomies = array(
            'pa_maand',
            'pa_aantal-vrijwilligers',
            'pa_leeftijd',
            'pa_lokale-bijdrage',
            'pa_projectcode',
            'pa_projectnaam',
            'pa_startdatum',
            'pa_einddatum',
		);
		
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			) );
			foreach ( $terms as $term ) {
				$data[] = array( 'taxonomy' => $taxonomy, 'term_slug' => $term->slug );
	
			}
		}
		return $data;


		//TODO:taxonomieën volgorde
	}

    /**
     * Bijwerken term
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

        $taxonomy = $item['taxonomy'];
        $term_slug = $item['term_slug']; 

		$term = get_term_by( 'slug', $term_slug, $taxonomy );

        //Verwijderen
        if ( 0 == $term->count ) {
			wp_delete_term( $term->term_id, $taxonomy );
			$this->increment_processed_count();
        }
		
		return false;
	}


	/**
	 * YITH widgets bijwerken
	 *
	 * - Naam van term bijwerken in label
	 *
	 * @return void
	 */
	protected function update_yith_widgets() {
		$widgets = get_option( 'widget_yith-woo-ajax-navigation' );
		$attributes = array(
			'maand',
			'land',
			'soort-werk',
			'doelgroep',
		);
	
		foreach ( $widgets as $index => $widget ) {
	
			if ( isset( $widget['attribute'] ) && in_array( $widget['attribute'], $attributes ) ) {
				$terms = get_terms( array(
					'taxonomy' => 'pa_' . $widget['attribute'],
					'hide_empty' => false,
				));
				$labels = array();
				foreach ( $terms as $term ) {
					$labels[ $term->term_id ] = $term->name;
				}
	
				$widgets[ $index ]['labels'] =  $labels;
			}
		}
		update_option( 'widget_yith-woo-ajax-navigation', $widgets );
	}


	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function complete() {
		$this->update_yith_widgets();
		parent::complete();
	}

}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Bijwerken taxonomiën', 'siw' ) );
	siw_register_background_process( 'SIW_Update_Taxonomies', 'update_taxonomies', $node, $parent_nodes, true );
} );
