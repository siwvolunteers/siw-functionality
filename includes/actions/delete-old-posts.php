<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Interfaces\Actions\Action as Action_Interface;

/**
 * Proces om oude aanmeldingen te verwijderen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Delete_Old_Posts implements Action_Interface {
	
	/** Maximale leeftijd van post in maanden */
	const MAX_AGE_POST = 12;

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'delete_old_posts';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Verwijderen oude posts', 'siw' );
	}

	/** {@inheritDoc} */
	public function select_data() : array {

		$post_types = apply_filters( 'siw_delete_old_posts_post_types', [] );

		$data = get_posts(
			[
				'post_type'      => $post_types,
				'fields'         => 'ids',
				'posts_per_page' => -1,
			]
		);
		return $data;
	}

	/** {@inheritDoc} */
	public function process( $post_id ) {
		$post_type = get_post_type( $post_id );

		if ( ! $post_type ) {
			return false;
		}

		$limit = date( 'Y-m-d', time() - ( self::MAX_AGE_POST * MONTH_IN_SECONDS ) );
		$delete = apply_filters( "siw_delete_posts_delete_{$post_type}", false, $post_id, $limit );

		if ( $delete ) {
			wp_delete_post( $post_id, true );
		}
	}
}
