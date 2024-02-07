<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Abstracts\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			A11Y_Accordion_Tabs::class,
			Cookie_Consent::class,
			Frappe_Charts::class,
			Google_Tag_Manager::class,
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
