<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

use SIW\Structured_Data\Thing;

/**
 * Interface voor post types die verwijderd moeten worden
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Structured_Data {

	/** Geeft structured data voor post met id `post_id` terug */
	public function get_structured_data( int $post_id ): Thing;
}
