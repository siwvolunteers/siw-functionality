<?php declare(strict_types=1);

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
	protected string $endpoint = 'DownloadDocumentFile';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'downloaden bestand';

	/**
	 * Download het bestand
	 *
	 * @param string $identifier
	 * @param string $extension
	 * 
	 * @return string
	 */
	public function download( string $identifier, string $extension = null ) : ?string {

		//Download bestand
		$this->add_query_arg( 'fileIdentifier', $identifier );
		$temp_file = \download_url( $this->endpoint_url, self::TIMEOUT );

		// Afbreken als downloaden mislukt is
		if ( is_wp_error( $temp_file ) ) {
			return null;
		}
		if ( is_string( $extension ) ) {
			$temp_file_ext = "{$temp_file}.{$extension}";
			rename( $temp_file, $temp_file_ext );
			$temp_file = $temp_file_ext; 
		}
		return $temp_file;
	}
}
