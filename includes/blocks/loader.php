<?php declare(strict_types=1);

namespace SIW\Blocks;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\Blocks\Block as Block_Interface;

/**
 * Loader voor Blockulieren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'Blocks';
	}

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Blocks\Colofon::class,
			Blocks\Accordion::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $object ) {

		if ( is_a( $object, Block_Interface::class ) ) {
			$Block = new Block( $object );
			$Block->register();
		}
	}
}
