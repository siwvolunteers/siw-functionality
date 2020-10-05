<?php declare(strict_types=1);

namespace SIW\Async;

use SIW\Attachment;
use SIW\Util;

/**
 * Verwerk upload van stockfoto
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 */
class Process_Stockphoto_Upload extends Request {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'process_stockphoto_upload';

	/**
	 * Subdirectory voor de stockfoto's
	 */
	protected string $subdir = 'groepsprojecten/stockfotos';

	/**
	 * Base voor bestandsnaam
	 */
	protected string $filename_base = 'stockfoto';

	/**
	 * Titel voor afbeelding
	 */
	protected string $title = 'Stockfoto';

	/**
	 * {@inheritDoc}
	 */
	protected array $variables = [
		'file' => [
			'type'     => 'url',
			'array'    => false,
			'required' => true,
		],
		'continent' => [
			'type'  => 'string',
			'array' => false,
		],
		'country' => [
			'type'  => 'string',
			'array' => false,
		],
		'work_type' => [
			'type'  => 'string',
			'array' => true,
		],
	];

	/**
	 * {@inheritDoc}
	 */
	protected function process() {

		$attachment = new Attachment( 'image', $this->subdir );
		$attachment_id = $attachment->add( $this->data['file'], $this->filename_base, $this->title );

		$this->set_taxonomy_terms( $attachment_id );
	}

	/**
	 * Zet taxonomy terms van attachment
	 *
	 * @param int $attachment_id
	 * 
	 * @todo refactor
	 */
	protected function set_taxonomy_terms( int $attachment_id ) {

		//Continent
		if ( ! empty( $this->data['continent'] ) ) {
			$continent = siw_get_continent( $this->data['continent'] );
			$term_id = Util::maybe_create_term( 'siw_attachment_continent', $continent->get_slug(), $continent->get_name() );
			if ( $term_id ) {
				wp_set_object_terms(
					$attachment_id,
					$term_id,
					'siw_attachment_continent'
				);
			}
		}

		//Land
		if ( ! empty( $this->data['country'] ) ) {
			$country = siw_get_country( $this->data['country'] );
			$term_id = Util::maybe_create_term( 'siw_attachment_country', $country->get_slug(), $country->get_name() );
			if ( $term_id ) {
				wp_set_object_terms(
					$attachment_id,
					$term_id,
					'siw_attachment_country'
				);
			}
		}

		//Soort werk
		if ( ! empty( $this->data['work_type'] ) ) {
			foreach ( $this->data['work_type'] as $type ) {
				$work_type = siw_get_work_type( $type );
				$term_id = Util::maybe_create_term( 'siw_attachment_work_type', $work_type->get_slug(), $work_type->get_name() );

				if ( $term_id ) {
					wp_set_object_terms(
						$attachment_id,
						$term_id,
						'siw_attachment_work_type'
					);
				}
			}
		}
	}
}
