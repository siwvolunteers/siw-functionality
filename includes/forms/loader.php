<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Multi_Page_Form as Multi_Page_Form_Interface;
use SIw\Interfaces\Forms\Form_Processor as Form_Processor_Interface;
use SIW\Interfaces\Forms\Pre_Processor as Pre_Processor_Interface;
use SIW\Interfaces\Forms\Processor as Processor_Interface;
use SIW\Interfaces\Forms\Post_Processor as Post_Processor_Interface;

/**
 * Loader voor formulieren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'forms';
	}

	/** {@inheritDoc} */
	protected function get_classes(): array {
		return [
			Forms\Cooperation::class,
			Forms\ESC::class,
			Forms\Enquiry_General::class,
			Forms\Enquiry_Project::class,
			Forms\Info_Day::class,
			Forms\Leader_Dutch_Projects::class,
			Forms\Tailor_Made::class,
			Processors\Spam_Check::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $object ) {

		if ( is_a( $object, Form_Interface::class ) ) {
			$form = new Form( $object );
			
			if ( is_a( $object, Multi_Page_Form_Interface::class ) ) {
				$form->set_pages( $object );
			}
			
			$form->register();
		}

		if ( is_a( $object, Form_Processor_Interface::class ) ) {
			$processor = new Form_Processor( $object );
			if ( is_a( $object, Pre_Processor_Interface::class ) ) {
				$processor->set_pre_processor( $object );
			}
			if ( is_a( $object, Processor_Interface::class ) ) {
				$processor->set_processor( $object );
			}
			if ( is_a( $object, Post_Processor_Interface::class ) ) {
				$processor->set_post_processor( $object );
			}
		}
	}
}
