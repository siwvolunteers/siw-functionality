<?php

namespace SIW\Widgets;

use SIW\Util\Links;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Snel Zoeken - resultaten
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quick_Search_Results extends Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id ='quick_search_results';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'search';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Snel Zoeken - resultaat', 'siw');
		$this->widget_description = __( 'Toont zoekresultaten', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Groepsprojecten', 'siw' ),
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	public function initialize() {
		add_filter( 'query_vars', [ $this, 'register_query_vars'] );
		add_filter( 'rocket_cache_query_strings', [ $this, 'register_query_vars'] );
	}

	/**
	 * Registreert query vars
	 * 
	 * - Bestemming
	 * - Maand
	 *
	 * @param array $vars
	 * @return array
	 */
	public function register_query_vars( $vars ) {
		$vars[] = 'bestemming';
		$vars[] = 'maand';
		return $vars;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) { 

		$url = wc_get_page_permalink( 'shop' );
		$text = __( 'Bekijk alle projecten', 'siw' );

		/* Verwerk zoekargument bestemming*/
		$category_arg   = '';
		$category_slug  = sanitize_key( get_query_var( 'bestemming', false ) );
		$category       = get_term_by( 'slug', $category_slug, 'product_cat' );

		if ( is_a( $category, '\WP_Term') ) {
			$category_arg = sprintf( 'category="%s"', $category_slug );
			$url = get_term_link( $category->term_id );
			$text .= SPACE . sprintf( __( 'in %s', 'siw' ), $category->name );
		}

		/* Verwerk zoekargument maand*/
		$month_arg  = '';
		$month_slug = sanitize_key( get_query_var( 'maand', false ) );
		$month      = get_term_by( 'slug', $month_slug, 'pa_maand');
		if ( is_a( $month, '\WP_Term') ) {
			$month_id  = $month->term_id; 
			$month_arg = sprintf( 'attribute="maand" terms="%s"', $month_id );
			$url       = add_query_arg( 'filter_maand', $month_slug, $url );
			$text      .= SPACE . sprintf( __( 'in %s', 'siw' ), strtolower( $month->name ) );
		}

		/* Genereer output */
		$content =
			'<p>' .
			esc_html__( 'Met een Groepsproject ga je voor 2 tot 3 weken naar een project, de begin- en einddatum van het project staan al vast.', 'siw' ) . SPACE .
			esc_html__( 'Hieronder zie je een selectie van de mogelijkheden', 'siw' ) .
			'</p>' .
			do_shortcode( sprintf( '[products limit="6" columns="3" orderby="rand" visibility="visible" %s %s cache=false]', $category_arg, $month_arg ) ) .
			'<div style="text-align:center">' .
			Links::generate_button_link( $url, $text ) .
			'</div>';
		return $content;
	}
}
