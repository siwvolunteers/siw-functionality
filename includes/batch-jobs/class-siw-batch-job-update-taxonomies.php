<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om taxonomieën bij te werken
 *
 * - Naam bijwerken
 * - Lege terms verwijderen
 * - Volgorde bijwerken
 * 
 * @package   SIW\Batch-Jobs
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_Delete_Workcamps
 */
class SIW_Batch_Job_Update_Taxonomies extends SIW_Batch_Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_taxonomies';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken taxonomies';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'algemeen';

	/**
	 * Selecteer de terms van de relevante taxonomieën
	 *
	 * @return array
	 * 
	 * @todo delete_empty_term optie
	 */
	protected function select_data() {

		$taxonomies = [
			'pa_maand',
			'pa_land',
			'pa_taal',
			'pa_soort-werk',
			'pa_doelgroep',
			'product_tag'
		];
		
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

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

		$tax_query = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			],
		];
		$products = wc_get_products(
			[
				'limit'      => -1,
				'return'     => 'ids',
				'tax_query'  => $tax_query,
			]
		);
		$term_count = count( $products );

		if ( 0 == $term_count ) {
			wp_delete_term( $term->term_id, $taxonomy );
			$this->increment_processed_count();
			return false;
		}
		
		/* Naam bijwerken indien nodig */
		$term_name = $this->get_term_name( $taxonomy, $term_slug );
		if ( null != $term_name && $term->name != $term_name ) {
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

		$this->increment_processed_count();
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

			case 'pa_taal':
				$language = siw_get_language( $term_slug );
				$name = $language ? $language->get_name() : null;
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
		$max_age = SIW_Batch_Job_Delete_Workcamps::MAX_AGE_WORKCAMP_IN_MONTHS;
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