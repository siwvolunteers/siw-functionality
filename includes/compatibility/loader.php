<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

class Loader extends Class_Loader_Abstract {

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

	protected function load( string $class_name ) {

		if ( is_a( $class_name, I_Plugin::class, true ) ) {
			if ( ! is_plugin_active( $class_name::get_plugin_basename() ) ) {
				return;
			}
		}

		$class_name::init();
	}
}
