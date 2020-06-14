<?php

namespace SIW\Elements;

/**
 * Taxonomy-filter voor archiefpagina's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Taxonomy_Filter {

	/**
	 * Opties
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Constructor
	 */
	public function __construct( array $options = [] ) {
		$this->options = wp_parse_args(
			$options,
			[
				'masonry'        => true,
				'use_post_count' => true,
			]
		);
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	/**
	 * Voegt script toe
	 */
	public function enqueue_script(){
		wp_register_script( 'siw-taxonomy-filter', SIW_ASSETS_URL . 'js/elements/siw-taxonomy-filter.js', [], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			'siw-taxonomy-filter',
			'siw_taxonomy_filter',
			[]
		);
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
		//TODO: afbreken bij fout; ob_start gebruiken
		$taxonomy_name = get_taxonomy( $taxonomy )->labels->name;
		$output = sprintf( '<div class="filter-button-group" data-filter-group="%s">', $taxonomy );
		$output .= '<h5>' . sprintf( esc_html__( 'Filter op %s', 'siw' ), strtolower( $taxonomy_name ) ) . '</h5>';
		$output .= sprintf ( '<button class="button ghost is-checked" data-filter="">%s</button>', esc_html__( 'Alle', 'siw' ) );
		foreach ( $terms as $term ) {
			$output .= sprintf( '<button class="button ghost" data-filter="%s">%s</button>', esc_attr( $term->slug ), esc_html( $term->name ) );
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
		$term_query = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,

		];

		if ( $this->options['use_post_count'] ) {
			$term_query['meta_query'] = [
				[
					'key'     => 'post_count',
					'value'   => 0,
					'compare' => '>',
				],
			];
		}
		return get_terms( $term_query );
	}
}
