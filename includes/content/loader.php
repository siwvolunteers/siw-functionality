<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Abstracts\Base_Loader;

/**
 * Loader voor content types classes
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Base_Loader {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Post_Types\Event::class,
			Post_Types\Job_Posting::class,
			Post_Types\Story::class,
			Post_Types\TM_Country::class,
		];
	}
}
