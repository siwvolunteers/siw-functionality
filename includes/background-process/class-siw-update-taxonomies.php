<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om taxonomieën bij te werken
 *
 * - Naam bijwerken
 * - Lege taxonomieën verwijderen
 * - Volgorde bijwerken
 * 
 * @package   SIW\Background-Process
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Update_Taxonomies extends SIW_Background_Process {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_taxonomies_process';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken taxonomies';

	/**
	 * Selecteer de terms van de relevante taxonomieën
	 *
	 * @return array
	 * 
	 * @todo taxonomies verwijderen die nu product attributes zijn geworden
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
			'pa_land',
			'pa_soort-werk',
			'pa_doelgroep',
			'product_tag'
		];
		
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );
			foreach ( $terms as $term ) {
				$data[] = [
					'taxonomy'  => $taxonomy,
					'term_slug' => $term->slug
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

		$term = get_term_by( 'slug', $term_slug, $taxonomy );

		if ( ! is_a( $term, 'WP_Term') ) {
			return false;
		}

		if ( 0 == $term->count ) {
			wp_delete_term( $term->term_id, $taxonomy );
			$this->increment_processed_count();
			return false;
		}
		
		/* Naam bijwerken indien nodig */
		$term_name = $this->get_term_name( $taxonomy, $term_slug );
		if ( null != $term_name || $term->name != $term_name ) {
			wp_update_term(
				$term->term_id,
				$taxonomy,
				[ 'name' => $term_name ]
			);
		}

		/* Volgorde bijwerken van toepassing */
		$term_order = $this->get_term_order( $taxonomy, $term_slug );
		if ( null != $term_order ) {
			update_term_meta( $term->term_id, "order_{$taxonomy}", $term_order ); 
		}
		return false;
	}

	/**
	 * Geeft naam van term terug o.b.v. reference data
	 *
	 * @param string $taxonomy
	 * @param string $term_slug
	 * 
	 * @return string
	 * 
	 * @todo doelgroep en product_tag
	 */
	protected function get_term_name( $taxonomy, $term_slug ) {
		switch ( $taxonomy ) {
			case 'pa_maand':
				$months = $this->get_months();
				if ( array_key_exists( $term_slug, $months ) ) {
					$name = $months[ $term_slug ]['name'];
				}
				else {
					$name = null;
				}
				break;
			case 'pa_land':
				$country = siw_get_country( $term_slug );
				$name = $country ? $country->get_name() : null;
				break;
			case 'pa_soort-werk':
				$work_type = siw_get_work_type( $term_slug );
				$name = $work_type ? $work_type->get_name() : null;
				break;
			case 'pa_doelgroep':
				$name = null;
				break;
			case 'product_tag':
				$name = null;
				break;
			default:
				$name = null;
		}

		return $name;

	}

	/**
	 * Geeft volgorde van term terug
	 *
	 * @param string $taxonomy
	 * @param string $term_slug
	 * @return string
	 */
	protected function get_term_order( $taxonomy, $term_slug ) {
		switch ( $taxonomy ) {
			case 'pa_maand':
				$months = $this->get_months();
				if ( array_key_exists( $term_slug, $months ) ) {
					$order = $months[ $term_slug ]['order'];
				}
				else {
					$order = null;
				}
				break;

			default:
				$order = null;
		}
		return $order;
	}

	/**
	 * Geeft informatie over maanden terug
	 * 
	 * @return array
	 */
	protected function get_months() {
		$max_age = SIW_Delete_Workcamps::MAX_AGE_WORKCAMP_IN_MONTHS;
		$max_months_in_future = 12; //TODO: constante

		$current_year = $current_year = date( 'Y' );

		for ( $i = -6 ; $i <= 18; $i++) {
			$date = date( 'Y-m-d', strtotime( date( 'Y-m-01' ) . "+{$i} months" ));
			$year = date( 'Y', strtotime( $date ) );
			$month = SIW_Formatting::format_month( $date, true );
			$slug = sanitize_title( $month );
			$months[ $slug ] =[
				'name'  => ucfirst( SIW_Formatting::format_month( $date, ( $year != $current_year ) ) ),
				'order' => date( 'Ym', strtotime( $date ) ),
			];
		}
		return $months;
	}

	/**
	 * YITH widgets bijwerken
	 *
	 * - Naam van term bijwerken in label
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
					'taxonomy'   => 'pa_' . $widget['attribute'],
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
	 * Extra acties bij afronden batch job
	 * 
	 * - YITH Widgets bijwerken
	 */
	protected function complete() {
		$this->update_yith_widgets();
		parent::complete();
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [
		'workcamps' =>  [ 'title' => __( 'Groepsprojecten', 'siw' ) ],
	];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Bijwerken taxonomiën', 'siw' ) ];
	siw_register_background_process( 'SIW_Update_Taxonomies', 'update_taxonomies', $node, $parent_nodes, true );
} );
