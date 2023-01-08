<?php declare(strict_types=1);

namespace SIW\Abstracts;

use SIW\Base;

/**
 * Roept load functie aan voor classes die SIW\Base extenden
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class Base_Loader extends Loader {

	/** Laadt klasses */
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class ) => $this->load( $class ) );
	}

	/** Laadt 1 klasse */
	final protected function load( string $class ) {
		if ( ! is_a( $class, Base::class ) ) {
			return;
		}
		$class::init();
	}
}
