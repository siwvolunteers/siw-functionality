<?php

namespace SIW\Plato;

/**
 * Download file uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Download_File extends Plato_Interface {

	/**
	 * Timeout bij downloaden
	 * 
	 * @var int
	 */
	const TIMEOUT = 60;

	/**
	 * {@inheritDoc}
	 */
	protected $endpoint = 'DownloadDocumentFile';

	/**
	 * Download het bestand
	 *
	 * @param string $identifier
	 * @param string $extensione
	 * 
	 * @return string
	 */
	public function download( string $identifier, string $extension = null ) {

		//Download bestand
		$this->add_query_arg( 'fileIdentifier', $identifier );
		$temp_file = \download_url( $this->endpoint_url, self::TIMEOUT );

		// Afbreken als downloaden mislukt is
		if ( is_wp_error( $temp_file ) ) {
			return null;
		}
		if ( null !== $extension ) {
			$temp_file_ext = "{$temp_file}.{$extension}";
			rename( $temp_file, $temp_file_ext );
			$temp_file = $temp_file_ext; 
		}

		return $temp_file;
	}
}
