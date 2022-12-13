<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\Options\Option as Option_Interface;

/**
 * Class om opties te laden
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'options';
	}

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Configuration::class,
			Help::class,
			Settings::class,
		];
	}

	/** {@inheritDoc} */
	public function get_interface_namespace(): string {
		return 'Options';
	}

	/** {@inheritDoc} */
	protected function load( object $option ) {
		if ( ! is_a( $option, Option_Interface::class ) ) {
			return;
		}
		new Option( $option );
	}
}
