<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Taxonomy-filter voor archiefpagina's
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Taxonomy_Filter extends Element {

	protected \WP_Taxonomy $taxonomy;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'taxonomy' => $this->taxonomy,
			'all'      => [
				'active' => empty( get_query_var( $this->taxonomy->query_var ) ) ? 'active' : '',
				'url'    => remove_query_arg( $this->taxonomy->query_var ),
			],
			'terms'    => $this->get_terms(),
		];
	}

	/** Zet de taxonomie */
	public function set_taxonomy( \WP_Taxonomy $taxonomy ): self {
		$this->taxonomy = $taxonomy;
		return $this;
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
			'taxonomy'   => $this->taxonomy->name,
			'hide_empty' => true,
		];

		return array_map(
			fn( \WP_Term $term ): array => [
				'name'   => $term->name,
				'url'    => add_query_arg( $this->taxonomy->query_var, $term->slug ),
				'active' => get_query_var( $this->taxonomy->query_var ) === $term->slug ? 'active' : '',
			],
			get_terms( $term_query )
		);
	}
}
