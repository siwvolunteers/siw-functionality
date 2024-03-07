<?php declare(strict_types=1);

namespace SIW\Plato;

class Download_File extends Base {

	private const TIMEOUT = 60;

	#[\Override]
	protected function get_endpoint(): string {
		return 'DownloadDocumentFile';
	}

	public function download( string $identifier, string $extension = null ): ?string {

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$this->add_query_arg( 'fileIdentifier', $identifier );
		$temp_file = \download_url( $this->endpoint_url, self::TIMEOUT );

		if ( \is_wp_error( $temp_file ) ) {
			return null;
		}
		if ( is_string( $extension ) ) {
			$temp_file_ext = "{$temp_file}.{$extension}";

			WP_Filesystem();
			/** @var \WP_Filesystem_Base */
			global $wp_filesystem;

			$wp_filesystem->move( $temp_file, $temp_file_ext );
		}
		return $temp_file;
	}
}
