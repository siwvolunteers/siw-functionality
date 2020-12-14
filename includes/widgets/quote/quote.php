<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met quote
 *
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Quote
 * Description: Toont quote van deelnemer
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quote extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id ='quote';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'editor-quote';

	/**
	 * Taxonomy voor continent
	 */
	protected string $continent_taxonomy = 'siw_quote_continent';

	/**
	 * Taxonomy voor projectsoort
	 */
	protected string $project_type_taxonomy = 'siw_quote_project_type';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Quote', 'siw');
		$this->widget_description = __( 'Toont quote van deelnemer', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Ervaringen van deelnemers', 'siw' ),
			],
			'continent' => [
				'type'    => 'select',
				'label'   => __( 'Continent', 'siw' ),
				'options' => $this->get_taxonomy_options( $this->continent_taxonomy ),
			],
			'project_type' => [
				'type'    => 'select',
				'label'   => __( 'Projectsoort', 'siw'),
				'options' => $this->get_taxonomy_options( $this->project_type_taxonomy ),
			]
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ): array {
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

	/**
	 * Geeft lijst met opties terug
	 *
	 * @param string $taxonomy
	 *
	 * @return array
	 */
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
	protected function get_quote( $continent, $project_type ) : ?array {
		
		$tax_query = [];
		if ( ! empty( $continent ) ) {
			$tax_query[] = [
				'taxonomy' => $this->continent_taxonomy,
				'terms'    => $continent,
				'field'    => 'slug',
			];
		}
		if ( ! empty( $project_type ) ) {
			$tax_query[] = [
				'taxonomy' => $this->project_type_taxonomy,
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
			'project_type' => wp_get_post_terms( $post_id, $this->project_type_taxonomy )[0]->name,
		];
		return $quote;
	}
}
