<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Roept load functie aan voor class
 *
 * @copyright 2020-2022 SIW Internationale Vrijwilligersprojecten
 */
abstract class Class_Loader extends Loader {

	/** Laadt klasses */
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class ) => $this->load( $class ) );
	}

	/** Laadt 1 klasse */
	abstract protected function load( string $class );
}
