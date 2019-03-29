<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Export naar Plato
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Plato_Export extends SIW_Plato_Interface {

	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Productiemode
	 *
	 * @var bool
	 */
	protected $production;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_production();
	}

	/**
	 * Zet productiemode o.b.v. instelling
	 */
	protected function set_production() {
		$this->production = siw_get_option( 'plato_production_mode', false );
	}

	/**
	 * Voer de Plato-export uit
	 *
	 * @return array
	 */
	public function run( $data ) {
		
		if ( ! $this->production ) {
			return [
				'success'     => false,
				'imported_id' => '',
				'message'     => 'Productiemode staat niet aan',
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
	protected function send_xml() {
		$args = [
			'timeout'     => 60,
			'redirection' => 0,
			'headers'     => [ 
				'accept'       => 'application/xml',
				'content-type' => 'application/x-www-form-urlencoded'
			],
			'user-agent'  => 'siw.nl',
			'body'        => [
				'organizationWebserviceKey' => $this->webkey,
				'xmlData'                   => $this->xml_data
			],
		];
		$this->http_response = wp_safe_remote_post( $this->endpoint_url, $args );
		if ( false == $this->check_http_response() ) {
			return false;
		}
		$this->xml_response = simplexml_load_string( wp_remote_retrieve_body( $this->http_response ) );
		return true;
	}
}
