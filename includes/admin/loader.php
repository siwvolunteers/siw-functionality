<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader voor admin classes
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			Admin_Bar::class,
			Admin::class,
			Notices::class,
			Properties_Page::class,
			Shortcodes::class,
		];
	}

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'admin';
	}

	/** Laadt 1 klasse */
	 protected function load( string $class ) {
		$class::init();
	}

}