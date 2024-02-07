<?php declare(strict_types=1);

namespace SIW\Elements;

class Taxonomy_Filter extends Element {

	protected \WP_Taxonomy $taxonomy;

	#[\Override]
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

	public function set_taxonomy( \WP_Taxonomy $taxonomy ): self {
		$this->taxonomy = $taxonomy;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}

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
