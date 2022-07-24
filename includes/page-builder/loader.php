<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;

use SIW\Interfaces\Page_Builder\Style_Fields as Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Style_Group as Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Settings as Settings_Interface;

/**
 * Loader voor PageBuilder-extensies
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			Animation::class,
			Design::class,
			Layout::class,
			Visibility::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $extension ) {

		$builder = new Builder();

		// Voeg style toe (eventueel met groep)
		if ( is_a( $extension, Style_Group_Interface::class ) ) {
			$builder->add_style_group( $extension );
		}
		if ( is_a( $extension, Style_Fields_Interface::class ) ) {
			$builder->add_style_fields( $extension );
		}

		// Voeg settings toe
		if ( is_a( $extension, Settings_Interface::class ) ) {
			$builder->add_settings( $extension );
		}
	}
}
