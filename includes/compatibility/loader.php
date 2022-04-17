<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader voor compatibility classes
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			GeneratePress::class,
			Meta_Box::class,
			Safe_Redirect_Manager::class,
			SiteOrigin_Page_Builder::class,
			SiteOrigin_Widgets_Bundle::class,
			The_SEO_Framework::class,
			UpdraftPlus::class,
			WooCommerce::class,
			WordPress::class,
			WP_Rocket::class,
			WP_Sentry_Integration::class,
			WPML::class,
		];
	}

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'compatibility';
	}
	
	/** Laadt 1 klasse */
	protected function load( string $class ) {
		$class::init();
	}

}