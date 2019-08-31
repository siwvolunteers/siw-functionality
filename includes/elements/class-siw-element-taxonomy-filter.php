<?php

/**
 * Taxonomy-filter voor archiefpagina's
 * 
 * @package   SIW\Elements
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Element_Taxonomy_Filter {

	/**
	 * Versienummer
	 */
	const ISOTOPE_VERSION = '3.0.6';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	/**
	 * Voegt script toe
	 * 
	 * @todo isotope-script toevoegen na switch theme + siw script aanpassen
	 */
	public function enqueue_script(){
		wp_register_script( 'siw-taxonomy-filter', SIW_ASSETS_URL . 'js/siw-taxonomy-filter.js', [], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-taxonomy-filter' );
	}

	/**
	 * Genereert groep filterknoppen voor één taxonomy
	 *
	 * @param string $taxonomy
	 * @return string
	 */
	public function generate( string $taxonomy ) {
		$terms = $this->get_terms( $taxonomy );
		$name = 'continent';
		$output = sprintf( '<div class="filter-button-group" data-filter-group="%s">', $taxonomy );
		$output .= '<h5>' . sprintf( esc_html__( 'Filter op %s', 'siw' ), $name ) . '</h5>';
		$output .= sprintf ( '<button class="kad-btn is-checked" data-filter="">%s</button>', esc_html__( 'Alle', 'siw' ) );
		foreach ( $terms as $term ) {
			$output .= sprintf( '<button class="kad-btn" data-filter=".%s">%s</button>', esc_attr( $term->slug ), esc_html( $term->name ) );
		}
		$output .= '</div>';
		return $output;
	}

	/**
	 * Haalt terms van één taxonomy op
	 * 
	 * @return array
	 */
	protected function get_terms( string $taxonomy ) {
		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		] );
		return $terms;
	}

}
