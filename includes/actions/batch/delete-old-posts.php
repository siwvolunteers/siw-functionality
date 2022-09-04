<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

/**
 * Proces om oude aanmeldingen te verwijderen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Delete_Old_Posts implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'delete_old_posts';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Verwijderen oude posts', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function select_data(): array {

		$post_types = apply_filters( 'siw/delete_old_posts/post_types', [] );

		if ( empty( $post_types ) ) {
			return [];
		}

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

		$delete = apply_filters( 'siw/delete_old_posts/should_delete_post', false, $post_id, $post_type );

		if ( $delete ) {
			wp_delete_post( $post_id, true );
		}
	}
}
