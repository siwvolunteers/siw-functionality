<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Interfaces\Actions\Async as Async_Action_Interface;

use SIW\Helpers\Attachment;
use SIW\Util;

/**
 * Proces om stockfoto's te uploaden
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Process_Stockphoto_Upload implements Async_Action_Interface {

	/** Subdirectory voor de stockfoto's */
	protected string $subdir = 'groepsprojecten/stockfotos';

	/** Base voor bestandsnaam */
	protected string $filename_base = 'stockfoto';

	/** Titel voor afbeelding */
	protected string $title = 'Stockfoto';

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'process_stockphoto_upload';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Verwerk stockfoto upload', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_argument_count(): int { 
		return 4;
	}

	/** {@inheritDoc} */
	public function process( string $file = '', string $continent = '', string $country = '', array $work_types = [] ) {
		$attachment = new Attachment( 'image', $this->subdir );
		$attachment_id = $attachment->add( $file, $this->filename_base, $this->title );

		//Continent
		if ( ! empty( $continent ) ) {
			$continent = siw_get_continent( $continent );
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
		if ( ! empty( $country ) ) {
			$country = siw_get_country( $country );
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
		if ( ! empty( $work_types ) ) {
			foreach ( $work_types as $type ) {
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
