<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor content types met een custom titel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Title {

	/** Genereert slug */
	public function generate_title( array $data, array $postarr ): string;
}
