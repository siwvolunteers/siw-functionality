<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Basisklasse voor loader
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
abstract class Loader {

	/** Geeft ID van loader terug */
	abstract protected function get_id() : string;

	/** Geeft classes voor loader terug */
	abstract protected function get_classes() : array;

	/** Init */
	public static function init() {
		$self = new static();

		//Filter voor extensies
		$classes = apply_filters( "siw_{$self->get_id()}_loader_classes", $self->get_classes() );
		$self->load_classes( $classes );
	}

	/** Laadt klasses */
	abstract protected function load_classes( array $classes );


}
