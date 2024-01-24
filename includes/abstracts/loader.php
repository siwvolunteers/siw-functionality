<?php declare(strict_types=1);

namespace SIW\Abstracts;

abstract class Loader {

	abstract protected function get_classes(): array;

	public static function init() {
		$self = new static();
		$self->load_classes( $self->get_classes() );
	}

	abstract protected function load_classes( array $classes );
}
