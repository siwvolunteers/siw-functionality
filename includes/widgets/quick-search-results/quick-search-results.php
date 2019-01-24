<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met contactinformatie
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * 
 * Widget Name: SIW: Snel Zoeken - resultaten
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Quick_Search_Results_Widget extends SIW_Widget {

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
	function __construct() {
		$this->widget_name = __( 'Snel Zoeken - resultaat', 'siw');
		$this->widget_description = __( 'Toont zoektresultaten', 'siw' );
		$this->widget_fields = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Groepsprojecten', 'siw' ),
			],
		];
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function initialize() {
		add_filter( 'query_vars', [ $this, 'register_query_vars'] );
	}

	/**
	 * Undocumented function
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
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 

		$url = wc_get_page_permalink( 'shop' );
		$text = __( 'Bekijk alle projecten', 'siw' );

		/* Verwerk zoekargument bestemming*/
		$category_arg   = '';
		$category_slug  = sanitize_key( get_query_var( 'bestemming', false ) );
		$category       = get_term_by( 'slug', $category_slug, 'product_cat' );

		if ( is_a( $category, 'WP_Term') ) {   
			$category_arg = sprintf( 'category="%s"', $category_slug );
			$url = get_term_link( $category->term_id );
			$text .= SPACE . sprintf( __( 'in %s', 'siw' ), $category->name );
		}

		/* Verwerk zoekargument maand*/
		$month_arg  = '';
		$month_slug = sanitize_key( get_query_var( 'maand', false ) );
		$month      = get_term_by( 'slug', $month_slug, 'pa_maand');
		if ( is_a( $month, 'WP_Term') ) {
			$month_id   = $month->term_id; 
			$month_arg  = sprintf( 'attribute="maand" terms="%s"', $month_id );
			$url        = add_query_arg( 'filter_maand', $month_slug, $url );
			$text       .= SPACE . sprintf( __( 'in %s', 'siw' ), strtolower( $month->name ) );
		}

		/* Genereer output */
		$content =
			esc_html__( 'Met een Groepsproject ga je voor 2 tot 3 weken naar een project, de begin- en einddatum van het project staan al vast.', 'siw' ) . SPACE .
			esc_html__( 'Hieronder zie je een selectie van de mogelijkheden', 'siw' ) .
			do_shortcode( sprintf( '[product limit="6" columns="3" orderby="random" visibility="visible" %s %s]', $category_arg, $month_arg ) ) .
			'<div style="text-align:center">' .
			SIW_Formatting::generate_link( $url, $text, [ 'class' => 'kad-btn kad-btn-primary'] ) .
			'</div>';
		return $content;
	}
}