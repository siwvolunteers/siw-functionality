<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\Forms\Form as Form_Interface;

/**
 * Loader voor formulieren
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Forms\Cooperation::class,
			Forms\ESC::class,
			Forms\Enquiry_General::class,
			Forms\Enquiry_Project::class,
			Forms\Info_Day::class,
			Forms\Leader_Dutch_Projects::class,
			Forms\Newsletter::class,
			Forms\Tailor_Made::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $object ) {

		if ( ! is_a( $object, Form_Interface::class ) ) {
			return;
		}
		$form = new Form( $object );
		$form->register();
	}
}
