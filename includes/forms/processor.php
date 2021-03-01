<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Interfaces\Forms\Processor as Processor_Interface;

/**
 * Processor
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Processor {

	/** Processor */
	protected Processor_Interface $processor;

	/** Init */
	public function __construct( Processor_Interface $processor ) {
		$this->processor = $processor;
		add_filter( 'caldera_forms_get_form_processors', [ $this, 'add_form_processor'] );
	}

	/** Voegt processer toe */
	public function add_form_processor( array $processors ) : array {
		$processors[ "siw_{$this->processor->get_id()}" ] = [
			'name'          => $this->processor->get_name(),
			'description'   => $this->processor->get_description(),
			'pre_processor' => [ $this->processor, 'preprocess'],
			'single'        => true,
		];
		return $processors;
	}
}