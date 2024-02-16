<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	protected function get_classes(): array {
		return [
			Forms\Cooperation::class,
			Forms\ESC::class,
			Forms\Enquiry_General::class,
			Forms\Enquiry_Project::class,
			Forms\Info_Day::class,
			Forms\Leader_Dutch_Projects::class,
			Forms\Newsletter::class,
			Forms\Tailor_Made::class,
		];
	}
}
