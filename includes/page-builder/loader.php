<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			Animation::class,
			CSS_Filters::class,
			Design::class,
			Visibility::class,
		];
	}
}
