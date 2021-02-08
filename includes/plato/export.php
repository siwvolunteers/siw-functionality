<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Util;

/**
 * Export naar Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Export extends Plato_Interface {

	/**
	 * Data
	 */
	protected array $data;

	/**
	 * XML-data
	 */
	protected string $xml_data;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Voer de Plato-export uit
	 *
	 * @return array
	 */
	public function run( $data ) : array {
		
		if ( ! Util::is_production() ) {
			return [
				'success'     => false,
				'imported_id' => '',
				'message'     => 'Geen productieomgeving',
			];
		}
		$this->data = $data;

		$this->log( 'info', sprintf( 'Start %s', $this->name ) );
		$this->generate_xml();
		if ( ! $this->send_xml() ) {
			return [
				'success'     => false,
				'imported_id' => '',
				'message'     => 'De verbinding met Plato is mislukt',
			];
		}
		$result = $this->process_xml();
		$this->log( 'info', sprintf( 'Eind %s', $this->name ) );

		return $result;
	}

	/**
	 * Genereert xml
	 */
	abstract protected function generate_xml();

	/**
	 * Verwerkt xml
	 */
	abstract protected function process_xml();

	/**
	 * Verstuurt xml naar plato
	 * 
	 * @return bool
	 */
	protected function send_xml() : bool {
		$args = [
			'timeout'     => 60,
			'redirection' => 0,
			'headers'     => [ 
				'accept'       => 'application/xml',
				'content-type' => 'application/x-www-form-urlencoded',
			],
			'user-agent'  => 'siw.nl',
			'body'        => [
				'organizationWebserviceKey' => $this->webkey,
				'xmlData'                   => $this->xml_data
			],
		];
		$this->http_response = wp_safe_remote_post( $this->endpoint_url, $args );
		if ( ! $this->is_valid_response() ) {
			return false;
		}
		$this->xml_response = simplexml_load_string( wp_remote_retrieve_body( $this->http_response ) );
		return true;
	}
}
