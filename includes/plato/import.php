<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Helpers\HTTP_Request;

/**
 * Import uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Import extends Plato_Interface {

	/** Data voor background process */
	protected array $data = [];

	/** Constructor */
	public function __construct() {
		parent::__construct();
		$this->add_query_arg_webkey();
	}

	/** Voeg de Plato-webkey toe als query arg */
	protected function add_query_arg_webkey() {
		$this->add_query_arg( 'organizationWebserviceKey', $this->webkey );
	}
	
	/** Haal de XML op */
	protected function retrieve_xml() : bool {

		$response = HTTP_Request::create( $this->endpoint_url )
			->set_accept( HTTP_Request::APPLICATION_XML )
			->set_content_type( HTTP_Request::APPLICATION_XML )
			->get();
		if ( \is_wp_error( $response ) ) {
			return false;
		}
		$this->xml_response = $response;
		return true;
	}

	/** Verwerk de XML */
	abstract protected function process_xml();

	/** Voer de Plato-import uit */
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