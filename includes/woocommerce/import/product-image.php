<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Helpers\Attachment;
use SIW\Plato\Download_File as Plato_Download_File;

/**
 * Selecteren van afbeelding voor een Groepsproject
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Product_Image {

	private const PLATO_DOCUMENT_IDENTIFIER_META = 'plato_document_identifier';
	public const PLATO_PROJECT_ID_META = 'plato_project_id';

	/** Minimale breedte voor afbeeldingen */
	private const MIN_IMAGE_WIDTH = 600;

	/** Minimale hoogte voor afbeeldingen */
	private const MIN_IMAGE_HEIGHT = 600;

	/** Subdirectory voor projectfoto's */
	protected string $subdir = 'groepsprojecten/projectfotos';

	/** Haal projectafbeelding (uit Plato) op */
	public function get_project_image( array $identifiers, string $filename_base, string $project_id ): ?int {

		// Kijk of er al een attachment voor 1 van de identifiers is
		$project_images = get_posts(
			[
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => [
					[
						'key'     => self::PLATO_DOCUMENT_IDENTIFIER_META,
						'value'   => $identifiers,
						'compare' => 'IN',
					],
				],
			]
		);

		if ( count( $project_images ) > 0 ) {
			return $project_images[ array_rand( $project_images, 1 ) ];
		}

		// Als er nog geen attachment is probeer er dan 1 te downloaden
		$document_import = new Plato_Download_File();
		$attachment = new Attachment( 'image', $this->subdir );
		$attachment->set_minimum_resolution( self::MIN_IMAGE_WIDTH, self::MIN_IMAGE_HEIGHT );

		foreach ( $identifiers as $identifier ) {
			$temp_file = $document_import->download( $identifier, 'jpg' );

			if ( is_string( $temp_file ) ) {
				$attachment_id = $attachment->add( $temp_file, $filename_base, 'Projectfoto' );
				if ( is_int( $attachment_id ) ) {
					update_post_meta( $attachment_id, self::PLATO_DOCUMENT_IDENTIFIER_META, $identifier );
					update_post_meta( $attachment_id, self::PLATO_PROJECT_ID_META, $project_id );
					// wp_update_post() post parent zetten?
					return $attachment_id;
				}
			}
		}
		return null;
	}
}
