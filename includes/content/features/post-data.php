<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Slug as I_Slug;
use SIW\Interfaces\Content\Title as I_Title;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * TODO:
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Post_Data extends Base {

	/** Extra opties */
	protected I_Title $title;

	/** Slug */
	protected I_Slug $slug;

	/** Init */
	public function __construct( protected I_Type $type ) {}

	/** Voegt opties toe */
	public function set_title( I_Title $title ) {
		$this->title = $title;
	}

	/** Voegt opties toe */
	public function set_slug( I_Slug $slug ) {
		$this->slug = $slug;
	}

	#[Filter( 'wp_insert_post_data' )]
	/** Zet post data */
	public function set_post_data( array $data, array $postarr ): array {
		if ( in_array( $data['post_status'], [ 'draft', 'pending', 'auto-draft' ], true ) ) {
			return $data;
		}

		if ( $this->type->get_post_type() !== $data['post_type'] ) {
			return $data;
		}

		if ( isset( $this->title ) ) {
			$data['post_title'] = $this->title->generate_title( $data, $postarr );
		}

		if ( isset( $this->slug ) ) {
			$slug = sanitize_title( $this->slug->generate_slug( $data, $postarr ) );
			$data['post_name'] = wp_unique_post_slug( $slug, $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent'] );
		}

		return $data;
	}
}
