<?php declare(strict_types=1);

namespace SIW\Abstracts;

use SIW\Base;
use SIW\Util\Logger;

/**
 * Roept load functie aan voor classes die SIW\Base extenden
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class Base_Loader extends Loader {

	/** Laadt klasses */
	protected function load_classes( array $classes ) {
		array_walk( $classes, fn( string $class_name ) => $this->load( $class_name ) );
	}

	/** Laadt 1 klasse */
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
