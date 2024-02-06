<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Abstracts\Object_Loader;
use SIW\Interfaces\Forms\Form as Form_Interface;

class Loader extends Object_Loader {

	#[\Override]
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

	#[\Override]
	protected function load( object $form_object ) {

		if ( ! is_a( $form_object, Form_Interface::class ) ) {
			return;
		}
		Form::init( $form_object );
	}
}
