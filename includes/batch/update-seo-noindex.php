<?php

namespace SIW\Batch;

use SIW\Util;

/**
 * Batch job om SEO noindex bij te werken
 * 
 * @copyright 2019 Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_SEO_Noindex extends Job {

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
		if ( ! Util::post_exists( $post_id ) ) {
			return false;
		}
		$current_noindex = $this->get_noindex( $post_id );

		$post_type = get_post_type( $post_id );
		switch ( $post_type ) {
			case 'vacatures':
				$deadline  = date( 'Y-m-d', get_post_meta( $post_id, 'siw_vacature_deadline', true ) );
				$new_noindex = date( 'Y-m-d' ) > $deadline;
				break;
			case 'agenda':
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
			$this->set_noindex( $post_id, $new_noindex );
			$this->increment_processed_count();
		}
		return false;
	}

	/**
	 * Haalt noindex van post op
	 *
	 * @param int $post_id
	 * @return bool
	 */
	protected function get_noindex( int $post_id ) {
		return (bool) get_post_meta( $post_id, '_genesis_noindex', true );
	}

	/**
	 * Zet SEO noindex
	 * 
	 * @param int $post_id
	 * @param bool $value
	 */
	protected function set_noindex( int $post_id, bool $value = false ) {
		$noindex = $value ? 1 : 0;
		update_post_meta( $post_id, '_genesis_noindex', $noindex );
	}


}
