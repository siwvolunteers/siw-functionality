<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Abstracts\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	protected function get_classes(): array {
		return [
			Help::class,
			Settings::class,
		];
	}
}
