<?php declare(strict_types=1);

namespace SIW;

use SIW\Abstracts\Base_Loader;

class Loader extends Base_Loader {

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Asset_Attributes::class,
			Scheduler::class,
			Update::class,
		];
	}
}
