<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstracte klasse voor interface met Plato (import en export)
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Plato_Interface {

	/**
	 * Naam van import/export (voor logging)
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Organization webkey van plato
	 *
	 * @var string
	 */
	protected $webkey;

	/**
	 * Endpoint van de webservice
	 * 
	 * @var string
	 */
	protected $endpoint;

	/**
	 * Endpoint url voor Plato
	 *
	 * @var string
	 */
	protected $endpoint_url;

	/**
	 * @var array
	 */
	protected $http_response;

	/**
	 * @var SimpleXML
	 */
	protected $xml_response;

	/**
	 * @var WC_Logger
	 */
	protected $logger;

	/**
	 * @var array
	 */
	protected $logger_context;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_logger();
		$this->set_webkey();
		$this->set_endpoint_url();
	}

	/**
	 * Zet Plato-webkey
	 */
	protected function set_webkey() {
		$this->webkey = siw_get_option( 'plato_organization_webkey' );
	}

	/**
	 * Geeft Plato-webkey terug
	 *
	 * @return string
	 */
	protected function get_webkey() {
		return $this->webkey;
	}

	/**
	 * Zet URL van het endpoint
	 */
	protected function set_endpoint_url() {
		$this->endpoint_url = SIW_Properties::PLATO_WEBSERVICE_URL . $this->endpoint;
	}
	
	/**
	 * Voegt query argument toe aan endpoint URL
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function add_query_arg( $key, $value ) {
		$this->endpoint_url = add_query_arg( $key, $value, $this->endpoint_url );
	}

	/**
	 * Zet (WooCommerce-)logger
	 */
	private function set_logger() {
		$this->logger = wc_get_logger();
		$source = sanitize_title( "siw-{$this->name}" );
		$this->logger_context = [ 'source' => $source ];
	}

	/**
	 * Schrijft boodschap naar log
	 *
	 * @param string $level
	 * @param string $message
	 * @return $this
	 */
	public function log( $level, $message ) {
		$this->logger->log( $level, $message, $this->logger_context );
		return $this;
	}

	/**
	 * Controleert de response van Plato
	 *
	 * @return bool
	 */
	protected function check_http_response() {
		/* In het geval van een fout: foutmelding wegschrijven naar log */
		if ( is_wp_error( $this->http_response ) ) {
			$this->log('error', 'Verbinding met PLATO mislukt. Response: ' . wc_print_r( $this->http_response ) );
			return false;
		}

		/* Zoek HTML-statuscode en breek af indien ongelijk aan 200 */
		$status_code = wp_remote_retrieve_response_code( $this->http_response );
		if ( '200' != $status_code ) {
			$this->log( 'error', "Verbinding met PLATO mislukt. Statuscode: {$status_code}" );
			return false;
		}

		return true;
	}

}