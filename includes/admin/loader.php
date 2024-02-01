<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Abstracts\Base_Loader as A_Base_Loader;

class Loader extends A_Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			Admin_Bar::class,
			Admin::class,
			Help_Page::class,
			Help_Tabs::class,
			Tableview_Page::class,
			Properties_Page::class,
			Page_Settings::class,
			Shortcodes::class,
		];
	}
}
