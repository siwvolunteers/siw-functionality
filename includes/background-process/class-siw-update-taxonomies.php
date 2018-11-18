<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om taxonomieën bij te werken
 * - Naam bijwerken
 * - Lege taxonomieën verwijderen
 * - Volgorde bijwerken
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 22018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Update_Taxonomies extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'update_taxonomies_process';

	/**
	 * @var string
	 */
	protected $name = 'bijwerken taxonomies';

	/**
	 * Selecteer de terms van de relevante taxonomieën
	 *
	 * @return array
	 */
	protected function select_data() {

		$taxonomies = [
			'pa_maand',
			'pa_aantal-vrijwilligers',
			'pa_leeftijd',
			'pa_lokale-bijdrage',
			'pa_projectcode',
			'pa_projectnaam',
			'pa_startdatum',
			'pa_einddatum',
		];
		
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( [
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			] );
			foreach ( $terms as $term ) {
				$data[] = [ 'taxonomy' => $taxonomy, 'term_slug' => $term->slug ];
	
			}
		}
		return $data;
	}

	/**
	 * Bijwerken term
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		$taxonomy = $item['taxonomy'];
		$term_slug = $item['term_slug']; 

		$term = get_term_by( 'slug', $term_slug, $taxonomy );

		//Verwijderen
		if ( is_a( $term, 'WP_Term') && 0 == $term->count ) {
			wp_delete_term( $term->term_id, $taxonomy );
			$this->increment_processed_count();
		}
		
		//TODO: naam bijwerken en volgorde
		
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
		$attributes = [
			'maand',
			'land',
			'soort-werk',
			'doelgroep',
		];
	
		foreach ( $widgets as $index => $widget ) {
	
			if ( isset( $widget['attribute'] ) && in_array( $widget['attribute'], $attributes ) ) {
				$terms = get_terms( [
					'taxonomy' => 'pa_' . $widget['attribute'],
					'hide_empty' => false,
				] );
				$labels = [];
				foreach ( $terms as $term ) {
					$labels[ $term->term_id ] = $term->name;
				}
	
				$widgets[ $index ]['labels'] =  $labels;
			}
		}
		update_option( 'widget_yith-woo-ajax-navigation', $widgets );
	}


	/**
	 * Volgorde en naam van attribute pa_month aanpassen
	 * @todo kan weg als alle terms bijgewerkt worden
	 * @return void
	 */
	protected function reorder_rename_product_attribute_month() {
		$terms = get_terms( 'pa_maand', [ 'hide_empty' => false ] );
		$ordered_terms = [];
		foreach ( $terms as $term ) {
			$ordered_terms[ $term->term_id ] = $term->slug;
		}
		//oplopend sorteren op slug
		asort( $ordered_terms, SORT_STRING );

		$order = 0;
		foreach ( $ordered_terms as $term_id => $term_slug ) {
			$name = siw_get_month_name_from_slug( $term_slug );

			//naam aanpassen
			wp_update_term( $term_id, 'pa_maand', [ 'name' => $name ] );
			$order++;
			//Volgorde bijwerken
			update_term_meta( $term_id, 'order_pa_maand', $order );
		}
	}


	/**
	 * Extra acties bij afronden batch job
	 * 
	 * - YITH Widgets bijwerken
	 * - Product-attribuut maand bijwerken
	 * 
	 * @return void
	 */
	protected function complete() {
		$this->update_yith_widgets();
		$this->reorder_rename_product_attribute_month();
		parent::complete();
	}

}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [
		'workcamps' =>  [ 'title' => __( 'Groepsprojecten', 'siw' ) ],
	];
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Bijwerken taxonomiën', 'siw' ) );
	siw_register_background_process( 'SIW_Update_Taxonomies', 'update_taxonomies', $node, $parent_nodes, true );
} );
