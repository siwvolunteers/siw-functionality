<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Basis-interface voor PageBuilder extensie
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Extension {

	/** Geeft aan of deze extensie widgets ondersteunt */
	public function supports_widgets(): bool;

	/** Geeft aan of deze extensie cells ondersteunt */
	public function supports_cells(): bool;

	/** Geeft aan of deze extensie rows ondersteunt */
	public function supports_rows(): bool;
}
