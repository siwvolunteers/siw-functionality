<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;

class Delete_Old_Posts extends Scheduled_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	public function get_name(): string {
		return __( 'Verwijderen oude posts', 'siw' );
	}

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::WEEKLY;
	}

	#[\Override]
	public function start(): void {
		$post_types = apply_filters( 'siw/delete_old_posts/post_types', [] );

		$data = get_posts(
			[
				'post_type'      => $post_types,
				'fields'         => 'ids',
				'posts_per_page' => -1,
			]
		);
		$this->enqueue_items( $data, self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function delete_old_post( int $post_id ) {
		if ( apply_filters( 'siw/delete_posts/should_delete', false, $post_id ) ) {
			wp_delete_post( $post_id, true );
		}
	}
}
