<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Helpers\HTTP_Request;
use SIW\Util\Logger;

/**
 * Import uit Plato
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class Import extends Plato_Interface {

	/** Data voor background process */
	protected array $data = [];

	/** Xsd bestand */
	protected string $xsd_file;

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

	/** Valideert XML tegen XSD */
	protected function validate_xml(): bool {
		$dom_element = dom_import_simplexml( $this->xml_response );
		if ( null === $dom_element ) {
			return false;
		}

		libxml_use_internal_errors( true );
		$dom = $dom_element->ownerDocument; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		if ( ! $dom->schemaValidate( $this->xsd_file ) ) {
			Logger::warning(
				sprintf( 'Fout tijdens schema validatie: %s', libxml_get_last_error()->message ),
				static::class
			);
			libxml_use_internal_errors( false );

			// TODO: dit moet false teruggeven zodra Plato wat betrouwbaarder is
			return true;
		}

		libxml_use_internal_errors( false );
		return true;
	}

	/** Voer de Plato-import uit */
	public function run() : array {
		// Start import
		if ( ! $this->retrieve_xml() || ! $this->validate_xml() ) {
			return [];
		}
		$this->process_xml();

		// Eind import
		return $this->data;
	}
}
