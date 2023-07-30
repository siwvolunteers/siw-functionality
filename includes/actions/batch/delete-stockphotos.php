<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as I_Batch_Action;

/**
 * Proces om oude aanmeldingen te verwijderen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Delete_Stockphotos implements I_Batch_Action {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'delete_stockphotos';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Verwijderen stockphotos', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		$attachment_ids = get_posts(
			[
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'title'          => 'Stockfoto',
				'fields'         => 'ids',
				'posts_per_page' => -1,
			]
		);

		return $attachment_ids;
	}

	/** {@inheritDoc} */
	public function process( $attachment_id ) {
		wp_delete_attachment( $attachment_id, true );
	}
}
