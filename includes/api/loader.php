<?php declare(strict_types=1);

namespace SIW\API;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\API\Endpoint as Endpoint_Interface;

/**
 * Loader voor API endpoints
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'api';
	}

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Newsletter_Signup::class,
			Postcode_Lookup::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $object ) {
		if ( ! is_a( $object, Endpoint_Interface::class ) ) {
			return;
		}
		new Endpoint( $object );
	}
}