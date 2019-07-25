<?php

/**
 * Batch job om SEO noindex bij te werken
 * 
 * @package   SIW\Batch-Jobs
 * @author    Maarten Bruna
 * @copyright 2019 Internationale Vrijwilligersprojecten
 */
class SIW_Batch_Job_Update_SEO_Noindex extends SIW_Batch_Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_seo_noindex';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken SEO noindex';

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
			'product'
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
	 * Bijwerken SEO noindex van posts
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	protected function task( $post_id ) {
		if ( ! SIW_Util::post_exists( $post_id ) ) {
			return false;
		}
		$current_noindex = SIW_Util::get_seo_noindex( $post_id );

		$post_type = get_post_type( $post_id );
		switch ( $post_type ) {
			case 'vacatures':
				$deadline  = date( 'Y-m-d', get_post_meta( $post_id, 'siw_vacature_deadline', true ) );
				$new_noindex = date( 'Y-m-d' ) > $deadline;
				break;
			case 'agenda':
				//$new_nodindex = true;
				$event_end  = date( 'Y-m-d', get_post_meta( $post_id, 'siw_agenda_eind', true ) );
				$new_noindex = date( 'Y-m-d' ) > $event_end;
				break;
			case 'product':
				$product = wc_get_product( $post_id );
				$new_noindex = ! $product->is_visible();
				break;
			default:
				return false;
		}

		if ( $current_noindex != $new_noindex ) {
			SIW_Util::set_seo_noindex( $post_id, $new_noindex );
			$this->increment_processed_count();
		}
		return false;
	}
}