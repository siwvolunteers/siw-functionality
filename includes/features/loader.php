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
	public function get_classes() : array {
		return [
			Animation::class,
			Mega_Menu::class,
			Social_Share::class,
			Topbar::class,
		];
	}
}
