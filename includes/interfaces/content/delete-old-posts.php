<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor post types die verwijderd moeten worden
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Delete_Old_Posts {

	/** Geeft aan of post met id `post_id` verwijderd moet worden */
	public function should_delete_post( int $post_id ): bool;
}
