<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Import uit Plato
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Plato_Import extends SIW_Plato_Interface {

	/**
	 * Data voor background process
	 *
	 * @var array
	 */
	protected $data = [];

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
		$this->add_query_arg( 'organizationWebserviceKey', $this->webkey, $this->endpoint_url );
	}
	
	/**
	 * Haal de XML op
	 *
	 * @return bool
	 */
	protected function retrieve_xml() {
	
		$args = [ 'timeout'	=> 60 ];
		$this->http_response = wp_safe_remote_get( $this->endpoint_url, $args );

		if ( false == $this->check_http_response() ) {
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