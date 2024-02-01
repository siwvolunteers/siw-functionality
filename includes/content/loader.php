<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Abstracts\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			Post_Types\Event::class,
			Post_Types\Job_Posting::class,
			Post_Types\Story::class,
			Post_Types\TM_Country::class,
		];
	}
}
