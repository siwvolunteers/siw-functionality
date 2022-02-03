<?php declare(strict_types=1);

namespace SIW;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	protected function get_id(): string {
		return 'root';
	}

	protected function get_classes(): array {
		return [
			Animation::class,
			Email::class,
			Cookie_Notice::class,
			Facebook_Pixel::class,
		];
	}

	/** Laadt 1 klasse */
	protected function load( string $class ) {
		$class::init();
	}
}
