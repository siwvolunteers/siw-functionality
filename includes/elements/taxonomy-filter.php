<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Actions\Batch\Update_Terms;

/**
 * Taxonomy-filter voor archiefpagina's
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomy_Filter extends Element {

	// Constantes voor assets handle
	const ASSETS_HANDLE = 'siw-taxonomy-filter';

	/** Taxonomie */
	protected string $taxonomy;

	/** Moet het aantal actieve posts gebruikt worden */
	protected bool $use_post_count = false;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'taxonomy-filter';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'taxonomy' => [
				'slug' => $this->taxonomy,
				'name' => get_taxonomy( $this->taxonomy )->labels->name,
			],
			'terms'    => $this->get_terms(),
			'i18n'     => [
				'all'    => __( 'Alle', 'siw' ),
				'filter' => __( 'Filter op', 'siw' ),
			],
		];
	}

	/** Zet de taxonomie */
	public function set_taxonomy( string $taxonomy ) {
		$this->taxonomy = $taxonomy;
		return $this;
	}

	/** Zet of het aantal actieve posts geteld moet worden */
	public function set_use_post_count( bool $use_post_count ) {
		$this->use_post_count = $use_post_count;
		return $this;
	}

	/** {@inheritDoc}*/
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/taxonomy-filter.js', [], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/taxonomy-filter.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/taxonomy-filter.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** Haalt terms van één taxonomy op */
	protected function get_terms(): array {
		$term_query = [
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => true,
		];

		if ( $this->use_post_count ) {
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
			fn( \WP_Term $term ): array => [
				'slug' => $term->slug,
				'name' => $term->name,
			],
			$terms
		);
	}
}
