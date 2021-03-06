<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Object loader
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
abstract class Object_Loader extends Loader {

	/** Laadt klasses */
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class ) => $this->load( new $class ) );
	}

	/** Laadt 1 klasse */
	abstract protected function load( object $object );
}
