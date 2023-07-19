<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Abstracts\Base_Loader;

/**
 * Loader voor modules
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Base_Loader {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			A11Y_Accordion::class,
			A11Y_Tablist::class,
			Flickity::class,
			Frappe_Charts::class,
			Leaflet::class,
		];
	}
}
