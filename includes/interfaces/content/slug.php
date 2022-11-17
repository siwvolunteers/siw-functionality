<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interfaces voor content types met een custom slug
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Slug {

	/** Genereert slug */
	public function generate_slug( array $data, array $postarr ): string;
}
