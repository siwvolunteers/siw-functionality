<?php

namespace SIW\WooCommerce\Import;

use SIW\Attachment;
use SIW\Data\Country;
use SIW\Data\Work_Type;
use SIW\Plato\Download_File as Plato_Download_File;

/**
 * Selecteren van afbeelding voor een Groepsproject
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Product_Image {

	/**
	 * Minimale breedte voor afbeeldingen
	 *
	 * @var int
	 */
	const MIN_IMAGE_WIDTH = 600;

	/**
	 * Minimale hoogte voor afbeeldingen
	 *
	 * @var int
	 */
	const MIN_IMAGE_HEIGHT = 600;

	/**
	 * Subdirectory voor projectfoto's
	 *
	 * @var string
	 */
	protected $subdir = 'groepsprojecten/projectfotos';

	/**
	 * Haal projectafbeelding (uit Plato) op
	 *
	 * @param array $identifier
	 * @param string $filename_base
	 * @param string $project_id
	 * 
	 * @return int|null
	 */
	public function get_project_image( array $identifiers, string $filename_base, string $project_id ) {

		// Kijk of er al een attachment voor 1 van de identifiers is
		$project_images = get_posts([
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => [
				[
					'key'     => 'plato_document_identifier',
					'value'   => $identifiers,
					'compare' => 'IN'
				],
			],
		]);

		if ( sizeof( $project_images ) > 0 ) {
			return $project_images[ array_rand( $project_images, 1 ) ];
		}

		//Als er nog geen attachment is probeer er dan 1 te downloaden
		$document_import = new Plato_Download_File;
		$attachment = new Attachment( 'image', $this->subdir );
		$attachment->set_minimum_resolution( self::MIN_IMAGE_WIDTH, self::MIN_IMAGE_HEIGHT );

		foreach ( $identifiers as $identifier ) {
			$temp_file = $document_import->download( $identifier, 'jpg' );

			if ( null !== $temp_file ) {
				$attachment_id = $attachment->add( $temp_file, $filename_base, 'Projectfoto' );
				if ( false !== $attachment_id ) {
					update_post_meta( $attachment_id, 'plato_document_identifier', $identifier );
					update_post_meta( $attachment_id, 'plato_project_id', $project_id );
					//wp_update_post() post parent zetten?
					return $attachment_id;
				}
			}
		}
		return null;
	}

	/**
	 * Zoekt stockfoto op basis van land en soort werk
	 *
	 * @param Country $country
	 * @param Work_Type[] $work_types
	 * @return int|null
	 */
	public function get_stock_image( Country $country, array $work_types ) {

		$continent_slug = $country->get_continent()->get_slug();
		$country_slug = $country->get_slug();
		foreach ( $work_types as $work_type ) {
			$work_type_slugs[] = $work_type->get_slug();
		}

		//Haal taxonomy queries op
		$tax_queries = $this->get_tax_queries( $continent_slug, $country_slug, $work_type_slugs );

		// Selecteer stock image
		foreach ( $tax_queries as $tax_query ) {

			$posts = get_posts( [
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'tax_query'   => $tax_query,
			]);

			//Random afbeelding kiezen als er resultaten zijn
			if ( sizeof( $posts ) > 0 ) {
				return $posts[ array_rand( $posts, 1 ) ];
			}
		}
		return null;
	}

	/**
	 * Maakt taxonomy queries aan
	 *
	 * @param string $continent
	 * @param string $country
	 * @param array $work_types
	 *
	 * @return array
	 */
	protected function get_tax_queries( string $continent, string $country, array $work_types ) {

		//Maak subqueries aan
		$country_query = [
			'taxonomy' => 'siw_attachment_country',
			'field'    => 'slug',
			'terms'    => $country,
		];
		$continent_query = [
			'taxonomy' => 'siw_attachment_continent',
			'field'    => 'slug',
			'terms'    => $continent,
		];
		$work_type_query = [
			'taxonomy' => 'siw_attachment_work_type',
			'field'    => 'slug',
			'terms'    => $work_types,
		];

		/*
		* Maak queries aan door subqueries te combineren
		*/

		//Land en soort werk
		$tax_queries[] = [
			'relation' => 'AND',
			$country_query,
			$work_type_query,
		];

		//Land
		$tax_queries[] = [
			'relation' => 'AND',
			$country_query,
		];

		//Continent en soort werk
		$tax_queries[] = [
			'relation' => 'AND',
			$continent_query,
			$work_type_query,
		];

		//Continent
		$tax_queries[] = [
			'relation' => 'AND',
			$continent_query,
		];

		return $tax_queries;
	}
}
