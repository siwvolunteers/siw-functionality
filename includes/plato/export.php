<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Helpers\HTTP_Request;
use SIW\Util;

/**
 * Export naar Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
abstract class Export extends Plato_Interface {

	/** Data */
	protected array $data;

	/** XML-data */
	protected string $xml_data;

	/** Constructor */
	public function __construct() {
		parent::__construct();
	}

	/** Voer de Plato-export uit */
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

	/** Genereert xml */
	abstract protected function generate_xml();

	/** Verwerkt xml */
	abstract protected function process_xml();

	/** Verstuurt xml naar plato */
	protected function send_xml() : bool {

		$response = HTTP_Request::create( $this->endpoint_url )
			->set_accept( HTTP_Request::APPLICATION_XML )
			->set_content_type( HTTP_Request::APPLICATION_X_WWW_FORM_URLENCODED )
			->post([
				'organizationWebserviceKey' => $this->webkey,
				'xmlData'                   => $this->xml_data
			]);

		if ( \is_wp_error( $response ) ) {
			return false;
		}
		$this->xml_response = $response;
		return true;
	}
}
