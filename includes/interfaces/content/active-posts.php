<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor post types met actieve posts
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Active_Posts {

	/** Geeft meta query terug om actieve posts te bepalen */
	public function get_active_posts_meta_query(): array;

	/** Is post met id `post_id` actief? */
	public function is_post_active( int $post_id ): bool;
}
