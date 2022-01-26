<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Abstracte klasse voor interface met Plato (import en export)
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Plato_Interface {

	/** Webservice url */
	const API_URL = 'https://workcamp-plato.org/files/services/ExternalSynchronization.asmx/';

	/** Naam van import/export (voor logging) */
	protected string $name;

	/** Organization webkey van plato */
	protected string $webkey;

	/** Endpoint van de webservice */
	protected string $endpoint;

	/** Endpoint url voor Plato */
	protected string $endpoint_url;

	/** XML response van Plato */
	protected \SimpleXMLElement $xml_response;

	/** Constructor */
	public function __construct() {
		$this->set_webkey();
		$this->set_endpoint_url();
	}

	/** Zet Plato-webkey */
	protected function set_webkey() {
		$this->webkey = siw_get_option( 'plato.organization_webkey' );
	}

	/** Geeft Plato-webkey terug */
	protected function get_webkey() : string {
		return $this->webkey;
	}

	/** Zet URL van het endpoint */
	protected function set_endpoint_url() {
		$this->endpoint_url = self::API_URL . $this->endpoint;
	}
	
	/** Voegt query argument toe aan endpoint URL */
	protected function add_query_arg( string $key, string $value ) {
		$this->endpoint_url = add_query_arg( $key, $value, $this->endpoint_url );
	}
}
