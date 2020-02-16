<?php

namespace SIW\Batch;

use SIW\Util;

/**
 * Batch job om oude posts te verwijderen
 * 
 * @copyright 2019 Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Delete_Old_Posts extends Job {

	/**
	 * Maximale leeftijd van post in maanden
	 * 
	 * @var int
	 */
	const MAX_AGE_POST = 12;

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'delete_old_posts';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'verwijderen oude posts';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'algemeen';

	/**
	 * Selecteer alle posts van de relevante post types
	 *
	 * @return array
	 * 
	 * @todo filter voor post_types toevoegen
	 */
	protected function select_data() {
		$post_types = [
			'vacatures',
			'agenda',
		];

		$data = get_posts(
			[
				'post_type'      => $post_types,
				'fields'         => 'ids',
				'posts_per_page' => -1,
			]
		);
		return $data;
	}

	/**
	 * Verwijderen van posts
	 *
	 * @param int $post_id
	 *
	 * @return mixed
	 * 
	 * @todo filter voor reference date
	 */
	protected function task( $post_id ) {
		if ( ! Util::post_exists( $post_id ) ) {
			return false;
		}

		$limit = date( 'Y-m-d', time() - ( self::MAX_AGE_POST * MONTH_IN_SECONDS ) );

		$post_type = get_post_type( $post_id );
		switch ( $post_type ) {
			case 'vacatures':
				$reference_date = date( 'Y-m-d', get_post_meta( $post_id, 'siw_vacature_deadline', true ) );
				break;
			case 'agenda':
				$reference_date  = date( 'Y-m-d', get_post_meta( $post_id, 'siw_agenda_eind', true ) );
				break;
			default:
				return false;
		}

		if ( $reference_date < $limit ) {
			wp_delete_post( $post_id, true );
			$this->increment_processed_count();
		}

		return false;
	}
}
