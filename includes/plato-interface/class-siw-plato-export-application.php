<?php

use Spatie\ArrayToXml\ArrayToXml;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Exporteert aanmelding Groepsproject naar Plato
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Plato_Export_Application extends SIW_Plato_Export{

	/**
	 * {@inheritDoc}
	 */
	protected $endpoint = 'ImportVolunteer';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'exporteren aanmelding';

	/**
	 * Undocumented function
	 */
	protected function generate_xml() {
		$this->xml_data = ArrayToXml::convert( $this->data, 'vef', true, 'UTF-8' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function process_xml() {
		$projectcode = $this->data['choice1']; //TODO: verplaatsen naar property
		$success = (bool) $this->xml_response->Success;
		if ( true == $success ) {
			$imported_id = (string) $this->xml_response->ImportedIds->string;
			$log_message = sprintf( 'Aanmelding voor %s succesvol geëxporteerd naar PLATO als %s.', $projectcode, $imported_id );
			$this->log( 'info', $log_message );
		}
		else {
			$error_messages = $body->ErrorMessages->string;
			$note = sprintf( 'Export naar PLATO van aanmelding voor %s mislukt.', $projectcode );
			$log_message = $note . wc_print_r( $error_messages );
			$this->log( 'error', $log_message );
		}
		return [
			'success'     => $success,
			'imported_id' => $imported_id ?? '',
			'message'     => $log_message,
		];
	}
}