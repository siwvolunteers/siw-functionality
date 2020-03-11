<?php

namespace SIW\Batch;

/**
 * Proces om verweesde variaties te verwijderen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Delete_Orphaned_Variations extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'delete_orphaned_variations';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'verwijderen verweesde variaties';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $schedule_job = false;

	/**
	 * Selecteer alle verweesde variaties
	 *
	 * @return array
	 */
	 protected function select_data() {
		$args = [
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'fields'         => 'ids',
			'post_status'    => 'any',
		];
		$products = get_posts( $args );
	
		//zoek alle product_variations zonder parent.
		$args = [
			'posts_per_page'      => -1,
			'post_type'           => 'product_variation',
			'post_parent__not_in' => $products,
			'fields'              => 'ids',
		];
		$variations = get_posts( $args );
	
		if ( empty( $variations ) ) {
			return false;
		}

		return $variations;
	}

	/**
	 * Verwijderen van verweesde variaties
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		$product = wc_get_product( $item );
		if ( ! $product instanceof \WC_Product ) {
			return false;
		}
		$product->delete( true );

		return false;
	}
}
