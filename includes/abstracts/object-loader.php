<?php declare(strict_types=1);

namespace SIW\Abstracts;

abstract class Object_Loader extends Loader {

	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class_name ) => $this->load( new $class_name() ) );
	}

	abstract protected function load( object $asset_object );
}
