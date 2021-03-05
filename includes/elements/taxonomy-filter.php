<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Actions\Batch\Update_Terms;
use SIW\Core\Template;

/**
 * Taxonomy-filter voor archiefpagina's
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomy_Filter {

	/** Opties */
	protected array $options = [];

	/** Constructor */
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

	/** Voegt script toe */
	public function enqueue_script(){
		wp_register_script( 'siw-taxonomy-filter', SIW_ASSETS_URL . 'js/elements/siw-taxonomy-filter.js', [], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			'siw-taxonomy-filter',
			'siw_taxonomy_filter',
			[]
		);
		wp_enqueue_script( 'siw-taxonomy-filter' );
	}

	/** Genereert groep filterknoppen voor één taxonomy */
	public function generate( string $taxonomy ) : string {
		$terms = $this->get_terms( $taxonomy );
		
		return Template::parse_template(
			'elements/taxonomy-filter',
			[
				'taxonomy' => [
					'slug'=> $taxonomy,
					'name' => get_taxonomy( $taxonomy )->labels->name,
				],
				'terms' => $terms,
				'i18n'  => [
					'all'    => __( 'Alle', 'siw' ),
					'filter' => __( 'Filter op', 'siw' )
				],
			]
		);
	}

	/** Haalt terms van één taxonomy op */
	protected function get_terms( string $taxonomy ) : array {
		$term_query = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		];

		if ( $this->options['use_post_count'] ) {
			$term_query['meta_query'] = [
				[
					'key'     => Update_Terms::POST_COUNT_TERM_META,
					'value'   => 0,
					'compare' => '>',
				],
			];
		}
		$terms = get_terms( $term_query );

		return array_map(
			fn( \WP_Term $term ) : array => [ 'slug' => $term->slug, 'name' => $term->name],
			$terms
		);
	}
}
