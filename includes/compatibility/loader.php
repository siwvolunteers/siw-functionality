<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			Action_Scheduler::class,
			GeneratePress::class,
			Members::class,
			Meta_Box::class,
			SiteOrigin_Page_Builder::class,
			SiteOrigin_Widgets_Bundle::class,
			WooCommerce::class,
			WordPress::class,
			WP_Sentry_Integration::class,
			WPML::class,
		];
	}
}
