<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Helpers\HTTP_Request;
use SIW\Util\Logger;

abstract class Import extends Base {

	protected array $data = [];

	public function __construct() {
		parent::__construct();
		$this->add_query_arg_webkey();
	}

	protected function add_query_arg_webkey() {
		$this->add_query_arg( 'organizationWebserviceKey', $this->webkey );
	}

	protected function retrieve_xml(): bool {
		$response = HTTP_Request::create( $this->endpoint_url )
			->set_accept( HTTP_Request::APPLICATION_XML )
			->set_content_type( HTTP_Request::APPLICATION_XML )
			->get();
		if ( \is_wp_error( $response ) ) {
			Logger::error(
				sprintf( 'Fout tijdens ophalen xml: %s', $response->get_error_message() ),
				static::class
			);
			return false;
		}
		$this->xml_response = $response;
		return true;
	}

	abstract protected function process_xml();

	protected function validate_xml(): bool {
		$dom_element = dom_import_simplexml( $this->xml_response );
		if ( null === $dom_element ) {
			return false;
		}

		libxml_use_internal_errors( true );
		$dom = $dom_element->ownerDocument; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		if ( ! $dom->schemaValidate( SIW_PLUGIN_DIR . "xsd/plato/{$this->get_endpoint()}.xsd" ) ) {
			Logger::warning(
				sprintf( 'Fout tijdens schema validatie: %s', libxml_get_last_error()->message ),
				__METHOD__
			);
			libxml_use_internal_errors( false );

			// TODO: dit moet false teruggeven zodra Plato wat betrouwbaarder is
			return true;
		}

		libxml_use_internal_errors( false );
		return true;
	}

	public function run(): array {
		if ( ! $this->retrieve_xml() || ! $this->validate_xml() ) {
			return [];
		}
		$this->process_xml();

		return $this->data;
	}
}
