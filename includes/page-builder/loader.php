<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;

use SIW\Interfaces\Page_Builder\Style_Attributes as I_Style_Attributes;
use SIW\Interfaces\Page_Builder\Style_CSS as I_Style_CSS;
use SIW\Interfaces\Page_Builder\Style_Fields as I_Style_Fields;
use SIW\Interfaces\Page_Builder\Style_Group as I_Style_Group;
use SIW\Interfaces\Page_Builder\Settings as I_Settings;

class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Animation::class,
			CSS_Filters::class,
			Design::class,
			Visibility::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $extension ) {

		$builder = new Builder();

		// Voeg style toe (eventueel met groep)
		if ( is_a( $extension, I_Style_Group::class ) ) {
			$builder->add_style_group( $extension );
		}
		if ( is_a( $extension, I_Style_Fields::class ) ) {
			$builder->add_style_fields( $extension );
		}

		if ( is_a( $extension, I_Style_Attributes::class ) ) {
			$builder->add_style_attributes( $extension );
		}

		if ( is_a( $extension, I_Style_CSS::class ) ) {
			$builder->add_style_css( $extension );
		}

		// Voeg settings toe
		if ( is_a( $extension, I_Settings::class ) ) {
			$builder->add_settings( $extension );
		}
	}
}
