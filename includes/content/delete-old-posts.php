<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Delete_Old_Posts as Delete_Old_Posts_Interface;
use SIW\Interfaces\Content\Type as Type_Interface;

/**
 * Voegt teller met actieve posts toe aan admin menu
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Delete_Old_Posts extends Base {

	/** {@inheritDoc} */
	protected function __construct( protected Type_Interface $type, protected Delete_Old_Posts_Interface $delete_old_posts ) {}

	/** Voegt post type toe aan post types om te verwijderen */
	#[Filter( 'siw/delete_old_posts/post_types' )]
	public function add_post_type( array $post_types ): array {
		$post_types[] = "siw_{$this->type->get_post_type()}";
		return $post_types;
	}

	#[Filter( 'siw/delete_old_posts/should_delete_post' )]
	/** Geeft aan of post met id `post_id` verwijderd moet worden */
	public function should_delete_post( bool $delete, int $post_id, string $post_type ): bool {

		if ( "siw_{$this->type->get_post_type()}" !== $post_type ) {
			return $delete;
		}
		return $this->delete_old_posts->should_delete_post( $post_id );
	}
}
