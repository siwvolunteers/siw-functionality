<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Taxonomy-filter voor archiefpagina's
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomy_Filter extends Element {

	/** Taxonomie */
	protected string $taxonomy;

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
	public function set_taxonomy( string $taxonomy ): self {
		$this->taxonomy = $taxonomy;
		return $this;
	}

	/** {@inheritDoc}*/
	public function enqueue_scripts() {
		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/elements/taxonomy-filter.js', [], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::get_assets_handle() );
	}

	/** Voegt styles toe */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/taxonomy-filter.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/taxonomy-filter.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

	/** Haalt terms van één taxonomy op */
	protected function get_terms(): array {
		$term_query = [
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => true,
		];

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
