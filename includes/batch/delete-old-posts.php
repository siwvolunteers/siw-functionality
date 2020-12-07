<?php declare(strict_types=1);

namespace SIW\Batch;

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
	protected string $name = 'verwijderen oude posts';

	/**
	 * {@inheritDoc}
	 */
	protected string $category = 'algemeen';

	/**
	 * Selecteer alle posts van de relevante post types
	 *
	 * @return array
	 * 
	 * @todo filter voor post_types toevoegen
	 */
	protected function select_data() : array {
		$post_types = [
			'vacatures',
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
		$post_type = get_post_type( $post_id );

		if ( ! $post_type ) {
			return false;
		}

		$limit = date( 'Y-m-d', time() - ( self::MAX_AGE_POST * MONTH_IN_SECONDS ) );
		
		switch ( $post_type ) {
			case 'vacatures':
				$reference_date = date( 'Y-m-d', get_post_meta( $post_id, 'siw_vacature_deadline', true ) );
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
