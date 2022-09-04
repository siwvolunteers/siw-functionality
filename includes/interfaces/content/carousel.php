<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor post types met carousel-support
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Carousel {

	/** Geeft template variabelen voor carousel item terug */
	public function get_carousel_template_variables( int $post_id ): array;

}
