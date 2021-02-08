<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Roept 
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
abstract class Class_Loader extends Loader {

	/** Laadt klasses */
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class ) => $this->load( $class ) );
	}

	/** Laadt 1 klasse */
	abstract protected function load( string $class );
}
