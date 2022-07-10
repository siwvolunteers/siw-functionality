<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;

use SIW\Interfaces\Page_Builder\Cell_Style_Fields as Cell_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Cell_Style_Group as Cell_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Row_Style_Fields as Row_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Row_Style_Group as Row_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Fields as Widget_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Group as Widget_Style_Group_Interface;
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
			Visibility::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $extension ) {

		$builder = new Builder();

		// Voeg row style toe (eventueel met groep)
		if ( is_a( $extension, Row_Style_Group_Interface::class ) ) {
			$builder->add_row_style_group( $extension );
		}
		if ( is_a( $extension, Row_Style_Fields_Interface::class ) ) {
			$builder->add_row_style_fields( $extension );
		}

		// Voeg cell style toe (eventueel met groep)
		if ( is_a( $extension, Cell_Style_Group_Interface::class ) ) {
			$builder->add_cell_style_group( $extension );
		}
		if ( is_a( $extension, Cell_Style_Fields_Interface::class ) ) {
			$builder->add_cell_style_fields( $extension );
		}

		// Voeg widget style toe (eventueel met groep)
		if ( is_a( $extension, Widget_Style_Group_Interface::class ) ) {
			$builder->add_widget_style_group( $extension );
		}
		if ( is_a( $extension, Widget_Style_Fields_Interface::class ) ) {
			$builder->add_widget_style_fields( $extension );
		}

		// Voeg settings toe
		if ( is_a( $extension, Settings_Interface::class ) ) {
			$builder->add_settings( $extension );
		}
	}
}
