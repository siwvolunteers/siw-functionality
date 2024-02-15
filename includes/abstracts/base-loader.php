<?php declare(strict_types=1);

namespace SIW\Abstracts;

use SIW\Base;
use SIW\Util\Logger;

abstract class Base_Loader {

	abstract protected function get_classes(): array;

	final public static function init() {
		$self = new static();
		$classes = $self->get_classes();
		array_walk( $classes, fn( string $class_name ) => $self->load( $class_name ) );
	}

	final protected function load( string $class_name ) {
		if ( ! is_a( $class_name, Base::class, true ) ) {
			Logger::warning(
				sprintf( '%s extend niet %s', $class_name, Base::class ),
				static::class
			);
			return;
		}
		$class_name::init();
	}
}
