<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Properties;

/**
 * Class om attachment aan te maken o.b.v. een (tijdelijk) bestand
 *
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 *
 * @todo        subclasses voor bestandstypes?
 */
class Attachment {

	/** Path van upload directory */
	protected string $upload_dir;

	/** URL van upload directory */
	protected string $upload_url;

	/** Minimum breedte van afbeelding */
	protected int $minimum_width;

	/** Minimum hoogte van afbeelding */
	protected int $minimum_height;

	/** Maximum breedte van afbeelding */
	protected int $maximum_width = Properties::MAX_IMAGE_SIZE;

	/** Maximum hoogte van afbeelding */
	protected int $maximum_height = Properties::MAX_IMAGE_SIZE;

	/** Init */
	public function __construct( protected string $filetype, protected string $subdir ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		// Bepaal standaard upload dir
		$upload_dir = \wp_upload_dir( null, false );
		$this->upload_dir = $upload_dir['basedir'];
		$this->upload_url = $upload_dir['baseurl'];

		// Zet subdirectory voor upload
		\add_filter( 'siw_upload_subdir', [ $this, 'set_upload_subdir' ] );
	}

	/** Voegt attachment toe */
	public function add( $temp_file, $filename, $title ): ?int {

		// Verplaats bestand naar upload-directory
		$relative_path = $this->move_file( $temp_file, $filename );
		if ( is_null( $relative_path ) ) {
			return null;
		}

		// Afbeelding controleren op minimale en maximale afmetingen
		if ( 'image' === $this->filetype ) {
			$relative_path = $this->check_image( $relative_path );
			if ( null === $relative_path ) {
				return null;
			}
		}
		return $this->create_attachment( $relative_path, $title );
	}

	/** Verplaatst bestand naar upload-directory */
	protected function move_file( string $temp_file, string $filename ): ?string {

		$temp_filename = basename( $temp_file );

		// Controleren bestand
		$check = \wp_check_filetype_and_ext( $temp_file, $temp_filename );
		if ( false === $check['type'] || ( null !== $this->filetype && wp_ext2type( $check['ext'] !== $this->filetype ) ) ) {
			\wp_delete_file( $temp_file );
			return null;
		}

		// Genereer bestandsnaam
		$filename .= ".{$check['ext']}";
		$filename = \wp_unique_filename( "{$this->upload_dir}/{$this->subdir}", $filename );

		// Bestand verplaatsen naar upload directory
		$file = [
			'name'     => $filename,
			'type'     => $check['type'],
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $temp_file ),
		];
		$overrides = [
			'test_form' => false,
		];
		$file_attributes = \wp_handle_sideload( $file, $overrides );
		return \_wp_relative_upload_path( $file_attributes['file'] );
	}

	/** Zet minimum resolutie van afbeelding */
	public function set_minimum_resolution( int $width, int $height ) {
		$this->minimum_width = $width;
		$this->minimum_height = $height;
	}

	/** Zet maximum resolutie van afbeelding */
	public function set_maximimum_resolution( int $width, int $height ) {
		$this->maximum_width = abs( $width );
		$this->maximum_height = abs( $height );
	}

	/** Voegt attachment toe aan database */
	protected function create_attachment( string $relative_path, string $title ): ?int {
		$file = \wp_normalize_path( $this->upload_dir . '/' . $relative_path );

		$attachment_id = \wp_insert_attachment(
			[
				'guid'           => $this->upload_url . '/' . $relative_path,
				'post_mime_type' => \wp_check_filetype( $file )['type'],
				'post_title'     => $title,
				'post_content'   => '',
				'post_status'    => 'inherit',
			],
			$relative_path
		);

		if ( \is_wp_error( $attachment_id ) ) {
			return null;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		\wp_update_attachment_metadata(
			$attachment_id,
			\wp_generate_attachment_metadata( $attachment_id, $file )
		);
		return $attachment_id;
	}

	/**
	 * Controleer afbeelding
	 *
	 * - Verwijderen en afbreken als afbeelding te klein is
	 * - Resizen als afbeelding te groot is
	 */
	protected function check_image( string $relative_path ): ?string {
		$file = wp_normalize_path( $this->upload_dir . '/' . $relative_path );

		$image_editor = \wp_get_image_editor( $file );
		if ( \is_wp_error( $image_editor ) ) {
			return $relative_path;
		}

		// Bepaal afmetingen van afbeelding
		$dimensions = $image_editor->get_size();

		// Afbeelding weggooien en afbreken als deze te klein is
		if ( ( isset( $this->minimum_width ) && $dimensions['width'] < $this->minimum_width ) || ( isset( $this->minimum_height ) && $dimensions['height'] < $this->minimum_height ) ) {
			\wp_delete_file( $file );
			return null;
		}

		// Resizen als afbeelding te groot is
		if ( $dimensions['width'] > $this->maximum_width || $dimensions['height'] > $this->maximum_height ) {
			$resize = $image_editor->resize( $this->maximum_width, $this->maximum_height, true );
			if ( \is_wp_error( $resize ) ) {
				\wp_delete_file( $file );
				return null;
			}
			$resized_image = $image_editor->save( $file );
			if ( \is_wp_error( $resized_image ) ) {
				return null;
			}

			$path = $resized_image['path'];
			if ( str_starts_with( $path, $this->upload_dir ) ) {
				$relative_path = str_replace( $this->upload_dir, '', $path );
				$relative_path = ltrim( $relative_path, '/' );
			}
			return $relative_path;
		}
		return $relative_path;
	}

	/** Zet upload directory */
	public function set_upload_subdir(): string {
		return $this->subdir;
	}
}
