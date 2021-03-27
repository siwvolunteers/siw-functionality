<?php declare(strict_types=1);

namespace SIW\Forms;

use SIw\Interfaces\Forms\Form_Processor as Form_Processor_Interface;
use SIW\Interfaces\Forms\Pre_Processor as Pre_Processor_Interface;
use SIW\Interfaces\Forms\Processor as Processor_Interface;
use SIW\Interfaces\Forms\Post_Processor as Post_Processor_Interface;

/**
 * Processor
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Form_Processor {

	/** Zet formprocessor */
	protected Form_Processor_Interface $form_processor;

	/** Zet preprocessor */
	protected Pre_Processor_Interface $pre_processor;

	/** Zet processor */
	protected Processor_Interface $processor;

	/** Zet postprocessor */
	protected Post_Processor_Interface $post_processor;
	
	/** Init */
	public function __construct( Form_Processor_Interface $form_processor ) {
		$this->form_processor = $form_processor;
		add_filter( 'caldera_forms_get_form_processors', [ $this, 'add_form_processor'] );
	}
	
	/** Voegt processer toe */
	public function add_form_processor( array $processors ) : array {

		$text = 1;

		$processors[ "siw_{$this->form_processor->get_id()}" ] = [
			'name'           => $this->form_processor->get_name(),
			'description'    => $this->form_processor->get_description(),
			'pre_processor'  => isset( $this->pre_processor ) ? [ $this->pre_processor, 'pre_process' ] : null,
			'processor'      => isset( $this->processor ) ? [ $this->processor, 'process' ] : null,
			'post_processor' => isset( $this->post_processor ) ? [ $this->post_processor, 'post_process' ] : null,
			'single'         => true,
		];

		return $processors;
	}

	/** Zet preprocessor */
	public function set_pre_processor( Pre_Processor_Interface $pre_processor ) {
		$this->pre_processor = $pre_processor;
	}

	/** Zet processor */
	public function set_processor( Processor_Interface $processor ) {
		$this->processor = $processor;
	}

	/** Zet postprocessor */
	public function set_post_processor( Post_Processor_Interface $post_processor ) {
		$this->post_processor = $post_processor;
	}
}