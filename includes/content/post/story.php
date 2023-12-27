<?php declare(strict_types=1);

namespace SIW\Content\Post;

use SIW\Data\Country;

class Story extends Post {

	public function get_thumbnail_id(): int {
		return $this->get_rows()[0]['image'][0] ?? 0;
	}

	/** {@inheritDoc} */
	public function get_excerpt(): string {
		return sprintf(
			'%s | %s',
			$this->get_continent()->name,
			$this->get_project_type()->name,
		);
	}

	public function get_name(): string {
		return $this->get_meta( 'name' );
	}

	public function get_country(): ?Country {
		return siw_get_country( $this->get_meta( 'country' ) );
	}

	public function get_continent(): \WP_Term {
		return $this->get_meta( 'siw_story_continent' );
	}

	public function get_project_type(): \WP_Term {
		return $this->get_meta( 'siw_story_project_type' );
	}

	public function get_rows(): array {
		return $this->get_meta( 'rows' );
	}
}
