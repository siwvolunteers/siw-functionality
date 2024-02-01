<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Util\Logger;
use Spatie\ArrayToXml\ArrayToXml;

/**
 * Exporteert aanmelding Groepsproject naar Plato
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Export_Application extends Export {

	#[\Override]
	protected string $endpoint = 'ImportVolunteer';

	/** Genereert XML */
	protected function generate_xml() {
		$this->xml_data = ArrayToXml::convert( $this->data, 'vef', true, 'UTF-8' );
	}

	#[\Override]
	protected function process_xml() {
		$projectcode = $this->data['choice1']; // TODO: verplaatsen naar property
		$success = (bool) $this->xml_response->Success;
		if ( $success ) {
			$imported_id = (string) $this->xml_response->ImportedIds->string;
			$log_message = sprintf( 'Aanmelding voor %s succesvol geëxporteerd naar PLATO als %s.', $projectcode, $imported_id );
		} else {
			$error_messages = $this->xml_response->ErrorMessages->string;
			$note = sprintf( 'Export naar PLATO van aanmelding voor %s mislukt.', $projectcode );
			$log_message = $note . wc_print_r( $error_messages );
			Logger::error( $log_message, 'siw_export_application' );
		}
		return [
			'success'     => $success,
			'imported_id' => $imported_id ?? '',
			'message'     => $log_message,
		];
	}
}
