<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Abstracts\Base_Loader as A_Base_Loader;

class Loader extends A_Base_Loader {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Animation::class,
			Breadcrumbs::class,
			Cookie_Consent::class,
			Email::class,
			Menu_Item_Info_Button::class,
			Facebook_Pixel::class,
			Fonts::class,
			Google_Tag_Manager::class,
			Icons::class,
			Iframe_Manager::class,
			Login::class,
			Plugin_Styles::class,
			Shortcodes::class,
			Social_Share::class,
			Tag_Attributes::class,
			Topbar::class,
			Upload_Subdir::class,
			Web_App_Manifest::class,
		];
	}
}
