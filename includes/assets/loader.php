<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Abstracts\Object_Loader;
use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Loader voor assets
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader {

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			A11Y_Accordion::class,
			A11Y_Tablist::class,
			Balloon::class,
			Flickity::class,
			Frappe_Charts::class,
			Google_Analytics::class,
			Google_Maps::class,
			JS_Cookie::class,
			Mapplic::class,
			Micromodal::class,
			Polyfill::class,
			Sal::class,
			SIW_Functionality::class,
			SIW_SVG::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $object ) {
		$asset = new Asset();
		if ( is_a( $object, Style::class ) ) {
			$asset->register_style( $object );
		}
		if ( is_a( $object, Script::class ) ) {
			$asset->register_script( $object );
		}
		if ( is_a( $object, External::class ) ) {
			$asset->register_external_asset( $object );
		}
	}
}
