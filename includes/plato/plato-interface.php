<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Abstracte klasse voor interface met Plato (import en export)
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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

	/**
	 * HTTP response van Plato
	 * 
	 * @var array|\WP_Error
	 */
	protected $http_response;

	/** XML response van Plato */
	protected \SimpleXMLElement $xml_response;

	/** Logger-instantie */
	protected \WC_Logger $logger;

	/** Logger context */
	protected array $logger_context;

	/** Constructor */
	public function __construct() {
		$this->set_logger();
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

	/** Zet (WooCommerce-)logger */
	private function set_logger() {
		$this->logger = wc_get_logger();
		$source = sanitize_title( "siw-{$this->name}" );
		$this->logger_context = [ 'source' => $source ];
	}

	/** Schrijft boodschap naar log */
	public function log( string $level, string $message ) {
		$this->logger->log( $level, $message, $this->logger_context );
	}

	/** Controleert de response van Plato */
	protected function is_valid_response() : bool {
		/* In het geval van een fout: foutmelding wegschrijven naar log */
		if ( is_wp_error( $this->http_response ) ) {
			$this->log('error', 'Verbinding met PLATO mislukt. Response: ' . wc_print_r( $this->http_response ) );
			return false;
		}

		/* Zoek HTML-statuscode en breek af indien ongelijk aan 200 */
		$status_code = wp_remote_retrieve_response_code( $this->http_response );
		if ( \WP_Http::OK !== $status_code ) {
			$this->log( 'error', "Verbinding met PLATO mislukt. Statuscode: {$status_code}" );
			return false;
		}
		return true;
	}
}
