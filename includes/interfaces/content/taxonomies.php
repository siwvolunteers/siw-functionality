<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor content type met taxonomieën
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Taxonomies {

	/** Geeft taxonomies terug */
	public function get_taxonomies(): array;
}
