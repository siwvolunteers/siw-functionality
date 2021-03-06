<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met quote
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Quote
 * Description: Toont quote van deelnemer
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quote extends Widget {

	/** Taxonomy voor continent */
	const CONTINENT_TAXONOMY = 'siw_quote_continent';

	/** Taxonomy voor projectsoort */
	const PROJECT_TYPE_TAXONOMY = 'siw_quote_project_type';

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quote';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Quote', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont quote van deelnemer', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'editor-quote';
	}

	/** {@inheritDoc} */
	function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Ervaringen van deelnemers', 'siw' ),
			],
			'continent' => [
				'type'    => 'select',
				'label'   => __( 'Continent', 'siw' ),
				'options' => $this->get_taxonomy_options( self::CONTINENT_TAXONOMY ),
			],
			'project_type' => [
				'type'    => 'select',
				'label'   => __( 'Projectsoort', 'siw' ),
				'options' => $this->get_taxonomy_options( self::PROJECT_TYPE_TAXONOMY ),
			]
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		$quote = $this->get_quote( $instance['continent'], $instance['project_type'] );
		
		if ( is_null( $quote ) ) {
			return [];
		}

		return [
			'quote'        => $quote['quote'],
			'name'         => $quote['name'],
			'project_type' => $quote['project_type'],
			'country'      => $quote['country'],
		];
	}

	/** Geeft lijst met opties terug */
	protected function get_taxonomy_options( string $taxonomy ) : array {
		$terms = get_terms( $taxonomy );
		$options[''] = __( 'Alle', 'siw' );
		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}
		return $options;
	}

	/**
	 * Haalt gegevens van quote op
	 *
	 * @param string $continent
	 * @param string $project_type
	 *
	 * @return array
	 */
	protected function get_quote( string $continent, string $project_type ) : ?array {
		
		$tax_query = [];
		if ( ! empty( $continent ) ) {
			$tax_query[] = [
				'taxonomy' => self::CONTINENT_TAXONOMY,
				'terms'    => $continent,
				'field'    => 'slug',
			];
		}
		if ( ! empty( $project_type ) ) {
			$tax_query[] = [
				'taxonomy' => self::PROJECT_TYPE_TAXONOMY,
				'terms'    => $project_type,
				'field'    => 'slug',
			];
		}

		$query_args = [
			'post_type'      => 'siw_quote',
			'posts_per_page' => 1,
			'orderby'        => 'rand',
			'fields'         => 'ids',
			'tax_query'      => $tax_query
		];
		$post_ids = get_posts( $query_args );

		if ( empty( $post_ids ) ) {
			return null;
		}

		$post_id = $post_ids[0];
		$quote = [
			'quote'        => get_post_meta( $post_id, 'quote', true ),
			'name'         => get_post_meta( $post_id, 'name', true ),
			'country'      => siw_get_country( get_post_meta( $post_id, 'country', true ) )->get_name(),
			'project_type' => wp_get_post_terms( $post_id, self::PROJECT_TYPE_TAXONOMY )[0]->name,
		];
		return $quote;
	}
}
