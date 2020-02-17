<?php

namespace SIW\Async;

/**
 * Uitbreiding van WP_Async_Request
 * 
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Request extends \WP_Async_Request {
	
	/**
	 * Prefix
	 * 
	 * @var string
	 */
	protected $prefix = 'siw';

	/**
	 * Variabelen voor process
	 *
	 * @var array
	 */
	protected $variables = [];

	/**
	 * {@inheritDoc}
	 */
	protected function handle() {
		//Haal data op
		$this->get_data();

		//Afbreken als data niet compleet is
		if ( ! $this->is_data_complete() ) {
			return;
		}
		$this->process();
	}

	/**
	 * Haalt data op uit post request (inclusief sanitizing)
	 *
	 * @return array
	 */
	protected function get_data() {
		$data = [];
		foreach ( $this->variables as $variable => $settings ) {
			$data[ $variable ] = [
				'filter' => $this->get_filter( $settings['type'] ),
				'flags'  => $settings['array'] ? FILTER_REQUIRE_ARRAY : FILTER_REQUIRE_SCALAR,
			];
		}
		$this->data = filter_input_array( INPUT_POST, $data );
	}

	/**
	 * Controleert of data compleet is
	 *
	 * @return bool
	 */
	protected function is_data_complete() {
		foreach ( $this->variables as $variable => $settings ) {
			if ( isset( $settings['required'] ) && $settings['required'] && empty( $this->data[ $variable ] ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Geeft filter terug op basis van type
	 *
	 * @param string $type
	 *
	 * @return int
	 */
	protected function get_filter( string $type ) {
		switch ( $type ) {
			case 'text':
				return FILTER_SANITIZE_STRING;
			case 'int':
				return FILTER_SANITIZE_NUMBER_INT;
			case 'float':
				return FILTER_SANITIZE_NUMBER_FLOAT;
			case 'email':
				return FILTER_SANITIZE_EMAIL;
			default:
				return FILTER_SANITIZE_STRING;
		}
	}

	/**
	 * Functie om gegevens te verwerken
	 */
	abstract protected function process();
}
