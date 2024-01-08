<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Abstracts\Base_Loader as A_Base_Loader;

/**
 * Loader voor modules
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends A_Base_Loader {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Animation::class,
			Breadcrumbs::class,
			Carbon_Badge::class,
			Cookie_Consent::class,
			Menu_Item_Info_Button::class,
			Facebook_Pixel::class,
			Google_Analytics_4::class,
			Icons::class,
			Iframe_Manager::class,
			Social_Share::class,
			Topbar::class,
			Web_App_Manifest::class,
		];
	}
}
