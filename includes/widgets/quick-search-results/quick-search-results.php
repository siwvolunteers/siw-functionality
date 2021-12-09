<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Snel Zoeken - resultaten
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quick_Search_Results extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quick_search_results';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Snel Zoeken - resultaat', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont zoekresultaten', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'search';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Groepsprojecten', 'siw' ),
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	public function initialize() {
		add_filter( 'query_vars', [ $this, 'register_query_vars'] );
		add_filter( 'rocket_cache_query_strings', [ $this, 'register_query_vars'] );
	}

	/**
	 * Registreert query vars
	 * 
	 * - Bestemming
	 * - Maand
	 */
	public function register_query_vars( array $vars ) : array {
		$vars[] = 'bestemming';
		$vars[] = 'maand';
		return $vars;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {

		//TODO:refactor
		$url = wc_get_page_permalink( 'shop' );
		$text = __( 'Bekijk alle projecten', 'siw' );

		/* Verwerk zoekargument bestemming*/
		$category_arg   = '';
		$category_slug  = sanitize_key( get_query_var( 'bestemming', false ) );
		$category       = get_term_by( 'slug', $category_slug, Taxonomy_Attribute::CONTINENT()->value );

		if ( is_a( $category, \WP_Term::class ) ) {
			$category_arg = sprintf( 'category="%s"', $category_slug );
			$url = get_term_link( $category->term_id );
			$text .= SPACE . sprintf( __( 'in %s', 'siw' ), $category->name );
		}

		/* Verwerk zoekargument maand*/
		$month_arg  = '';
		$month_slug = sanitize_key( get_query_var( 'maand', false ) );
		$month      = get_term_by( 'slug', $month_slug, Taxonomy_Attribute::MONTH()->value );
		if ( is_a( $month, \WP_Term::class ) ) {
			$month_id  = $month->term_id; 
			$month_arg = sprintf( 'attribute="maand" terms="%s"', $month_id );
			$url       = add_query_arg( 'filter_maand', $month_slug, $url );
			$text      .= SPACE . sprintf( __( 'in %s', 'siw' ), strtolower( $month->name ) );
		}

		return [
			'intro'     =>
				esc_html__( 'Met een Groepsproject ga je voor 2 tot 3 weken naar een project, de begin- en einddatum van het project staan al vast.', 'siw' ) . SPACE .
				esc_html__( 'Hieronder zie je een selectie van de mogelijkheden', 'siw' ),
			'shortcode' => sprintf( '[products limit="6" columns="3" orderby="rand" visibility="visible" %s %s cache=false]', $category_arg , $month_arg ),
			'button' => [
				'url'  => $url,
				'text' => $text,
			]
		];
	}
}
