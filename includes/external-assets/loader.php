<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Abstracts\Base_Loader;

/**
 * Loader voor externe assets
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Base_Loader {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			A11Y_Accordion_Tabs::class,
			Cookie_Consent::class,
			Flickity::class,
			Frappe_Charts::class,
			Google_Analytics_4::class,
			Iframe_Manager::class,
			Jsvectormap::class,
			Jsvectormap_World_Map::class,
			Leaflet::class,
			Meta_Pixel::class,
			Polyfill::class,
			Sal::class,
			Splide::class,
		];
	}
}
