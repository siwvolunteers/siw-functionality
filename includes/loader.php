<?php declare(strict_types=1);

namespace SIW;

use SIW\Abstracts\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	protected function get_classes(): array {
		return [
			Update::class,
		];
	}
}
