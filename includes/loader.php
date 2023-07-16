<?php declare(strict_types=1);

namespace SIW;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Asset_Attributes::class,
			Email::class,
			Login::class,
			Scheduler::class,
			Shortcodes::class,
			Update::class,
			Upload_Subdir::class,
		];
	}

	/** Laadt 1 klasse */
	protected function load( string $class ) {
		$class::init();
	}
}
