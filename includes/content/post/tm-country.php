<?php declare(strict_types=1);

namespace SIW\Content\Post;

use SIW\Data\Country;
use SIW\Data\Work_Type;

class TM_Country extends Post {

	public function get_thumbnail_id(): int {
		return $this->get_image_id();
	}

	public function get_country(): Country {
		return siw_get_country( $this->get_meta( 'country' ) );
	}

	public function get_continent(): \WP_Term {
		return $this->get_meta( 'siw_tm_country_continent' );
	}

	/** @return Work_Type[] */
	public function get_work_types(): array {
		return array_map(
			fn( string $work_type ): Work_Type => Work_Type::tryFrom( $work_type ),
			$this->get_meta( 'work_type' )
		);
	}

	public function has_child_projects(): bool {
		return ! empty(
			array_filter(
				$this->get_work_types(),
				fn( Work_Type $work_type ): bool => $work_type->needs_review(),
			)
		);
	}

	/** {@inheritDoc} */
	public function get_excerpt(): string {
		return $this->get_quote();
	}

	public function get_introduction(): string {
		return $this->get_meta( 'introduction' );
	}

	public function get_description(): string {
		return $this->get_meta( 'description' );
	}

	public function get_quote(): string {
		return $this->get_meta( 'quote' );
	}

	public function get_image_id(): int {
		$images = $this->get_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		return (int) $image['ID'] ?? 0;
	}
}
