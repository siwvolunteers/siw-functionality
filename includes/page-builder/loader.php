<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Abstracts\Loader as Loader_Abstract;

/**
 * Loader voor PageBuilder-extensies
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Loader extends Loader_Abstract {

	/**
	 * {@inheritDoc}
	 */
	protected string $id = 'page_builder';

	/**
	 * {@inheritDoc}
	 */
	protected string $interface_namespace = 'Page_Builder';

	/**
	 * {@inheritDoc}
	 */
	protected array $classes = [
		'Animation',
		'Design',
		'Layout',
		'Visibility',
	];

	/**
	 * {@inheritDoc}
	 */
	protected function load( object $extension ) {

		$builder = new Builder;
		//Voeg row style toe (eventueel met groep)
		if ( $this->implements_interface( $extension, 'Row_Style_Group' ) ) {
			$builder->add_row_style_group( $extension );
		}
		if ( $this->implements_interface( $extension, 'Row_Style_Fields' ) ) {
			$builder->add_row_style_fields( $extension );
		}

		//Voeg cell style toe (eventueel met groep)
		if ( $this->implements_interface( $extension, 'Cell_Style_Group' ) ) {
			$builder->add_cell_style_group( $extension );
		}
		if ( $this->implements_interface( $extension, 'Cell_Style_Fields' ) ) {
			$builder->add_cell_style_fields( $extension );
		}

		//Voeg widget style toe (eventueel met groep)
		if ( $this->implements_interface( $extension, 'Widget_Style_Group' ) ) {
			$builder->add_widget_style_group( $extension );
		}
		if ( $this->implements_interface( $extension, 'Widget_Style_Fields' ) ) {
			$builder->add_widget_style_fields( $extension );
		}

		//Voeg settings toe
		if ( $this->implements_interface( $extension, 'Settings' ) ) {
			$builder->add_settings( $extension );
		}
	}
}
