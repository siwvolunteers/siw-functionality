<?php declare(strict_types=1);

namespace SIW;

use SIW\Abstracts\Base_Loader;

/**
 * Loader
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Base_Loader {

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
}
