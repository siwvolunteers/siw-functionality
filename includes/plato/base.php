<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Config;

abstract class Base {

	private const API_URL = 'https://workcamp-plato.org/files/services/ExternalSynchronization.asmx/';

	protected string $webkey;
	protected string $endpoint;
	protected string $endpoint_url;
	protected \SimpleXMLElement $xml_response;

	abstract protected function get_endpoint(): string;

	public function __construct() {
		$this->set_webkey();
		$this->set_endpoint_url();
	}

	protected function set_webkey() {
		$this->webkey = Config::get_plato_organization_webkey();
	}

	protected function get_webkey(): string {
		return $this->webkey;
	}

	protected function set_endpoint_url() {
		$this->endpoint_url = self::API_URL . $this->get_endpoint();
	}

	protected function add_query_arg( string $key, string $value ) {
		$this->endpoint_url = add_query_arg( $key, $value, $this->endpoint_url );
	}
}
