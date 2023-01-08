<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader voor modules
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			Breadcrumbs::class,
			Google_Analytics::class,
			Mega_Menu::class,
			Topbar::class,
		];
	}

	/** Laadt 1 klasse */
	protected function load( string $class ) {
		$class::init();
	}
}
