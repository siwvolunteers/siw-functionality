<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Config;
use SIW\Helpers\HTTP_Request;

abstract class Export extends Base {

	protected array $data;
	protected string $xml_data;

	public function run( $data ): array {

		if ( ! Config::get_plato_export_applications() ) {
			return [
				'success'     => false,
				'imported_id' => '',
				'message'     => 'Aanmelding wordt niet geëxporteerd',
			];
		}
		$this->data = $data;

		$this->generate_xml();
		if ( ! $this->send_xml() ) {
			return [
				'success'     => false,
				'imported_id' => '',
				'message'     => 'De verbinding met Plato is mislukt',
			];
		}
		$result = $this->process_xml();

		return $result;
	}

	abstract protected function generate_xml();

	abstract protected function process_xml();

	protected function send_xml(): bool {

		$response = HTTP_Request::create( $this->endpoint_url )
			->set_accept( HTTP_Request::APPLICATION_XML )
			->set_content_type( HTTP_Request::APPLICATION_X_WWW_FORM_URLENCODED )
			->post(
				[
					'organizationWebserviceKey' => $this->webkey,
					'xmlData'                   => $this->xml_data,
				]
			);

		if ( \is_wp_error( $response ) ) {
			return false;
		}
		$this->xml_response = $response;
		return true;
	}
}
