<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;

class Update_Custom_Posts extends Scheduled_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	public function get_name(): string {
		return __( 'Bijwerken custom posts', 'siw' );
	}

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::WEEKLY;
	}

	#[\Override]
	public function start(): void {
		$post_types = apply_filters( 'siw/update_custom_posts/post_types', [] );

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
	public function update_post( int $post_id ) {
		if ( apply_filters( 'siw/update_custom_posts/should_delete', false, $post_id ) ) {
			wp_delete_post( $post_id, true );
			return;
		}

		$new_noindex = (int) ! apply_filters( 'siw/update_custom_posts/should_index', true, $post_id );

		$seo_data = get_post_meta( $post_id, 'slim_seo', true );

		if ( ! is_array( $seo_data ) ) {
			$seo_data = [];
		}

		$current_noindex = (int) ( $seo_data['noindex'] ?? false );
		if ( $current_noindex !== $new_noindex ) {
			$seo_data['noindex'] = $new_noindex;
			update_post_meta( $post_id, 'slim_seo', $seo_data );
		}
	}
}
