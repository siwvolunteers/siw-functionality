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
	 */
	protected function select_data() {
		$post_types = [ 'vacatures', 'agenda', 'product' ]; //TODO:filter o.i.d. vanuit CPT-class
		

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
		//$current_noindex = SIW_Util::get_seo_noindex( $post_id );

		$post_type = get_post_type( $post_id );
		switch ( $post_type ) {
			case 'vacatures':
				//$new_noindex = true;
				break;
			case 'agenda':
				//$new_nodindex = true;
				break;
			case 'product':
				//$product = wc_get_product( $post_id );
				//$new_noindex = ! $product->is_visible();
				break;
			default:
		}

		if ( true ) {// $current_noindex != $new_noindex ) {
			//SIW_Util::set_seo_noindex( $post_id, $new_noindex );
			$this->increment_processed_count();
			siw_debug( $post_id );
		}
		return false;
	}
}