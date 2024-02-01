<?php declare(strict_types=1);

namespace SIW\Abstracts;

abstract class Class_Loader extends Loader {

	#[\Override]
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class_name ) => $this->load( $class_name ) );
	}

	abstract protected function load( string $class_name );
}
