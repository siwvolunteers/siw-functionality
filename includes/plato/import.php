<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Import uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Import extends Plato_Interface {

	/**
	 * Data voor background process
	 */
	protected array $data = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->add_query_arg_webkey();
	}

	/**
	 * Voeg de Plato-webkey toe als query arg
	 */
	protected function add_query_arg_webkey() {
		$this->add_query_arg( 'organizationWebserviceKey', $this->webkey );
	}
	
	/**
	 * Haal de XML op
	 *
	 * @return bool
	 */
	protected function retrieve_xml() : bool {
	
		$args = [ 'timeout'	=> 60 ];
		$this->http_response = wp_safe_remote_get( $this->endpoint_url, $args );

		if ( ! $this->is_valid_response() ) {
			return false;
		}

		$this->xml_response = simplexml_load_string( wp_remote_retrieve_body( $this->http_response ) );
		return true;
	}

	/**
	 * Verwerk de XML
	 */
	abstract protected function process_xml();

	/**
	 * Voer de Plato-import uit
	 *
	 * @return array
	 */
	public function run() {
		//Start import
		$this->log( 'info', sprintf( 'Start %s', $this->name ) );

		if ( ! $this->retrieve_xml() ) {
			return false;
		}
		$this->process_xml();
		
		//Eind import
		$this->log( 'info', sprintf( 'Eind %s', $this->name ) );

		return $this->data;
	}
	
}