<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Basisklasse voor loader
 *
 * @copyright 2020-2022 SIW Internationale Vrijwilligersprojecten
 */
abstract class Loader {

	/** Geeft classes voor loader terug */
	abstract protected function get_classes(): array;

	/** Init */
	public static function init() {
		$self = new static();
		$self->load_classes( $self->get_classes() );
	}

	/** Laadt klasses */
	abstract protected function load_classes( array $classes );

}
