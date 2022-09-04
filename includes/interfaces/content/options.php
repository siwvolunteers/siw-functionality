<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor extra opties bij content type
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Options {

	/** Geeft opties terug */
	public function get_options(): array;
}
