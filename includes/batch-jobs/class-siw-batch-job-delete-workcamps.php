<?php

/**
 * Proces om oude Groepsprojecten te verwijderen
 * 
 * @package   SIW\Batch-Jobs
 * @author    Maarten Bruna
 * @copyright 2017-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Batch_Job_Delete_Workcamps extends SIW_Batch_Job {

	/**
	 * Aantal maanden voordat Groepsproject verwijderd wordt.
	 */
	const MAX_AGE_WORKCAMP_IN_MONTHS = 6;

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'delete_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'verwijderen groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer Groepsprojecten die meer dan 6 maanden geleden zijn begonnen
	 * @todo configuratieconstante voor aantal maanden
	 * @todo wc_get_products gebruiken
	 *
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() - ( self::MAX_AGE_WORKCAMP_IN_MONTHS * MONTH_IN_SECONDS ) );

		$meta_query = [
			'relation' => 'OR',
			[
				'key'     => 'start_date',
				'value'   => $limit,
				'compare' => '<',
			],
			[
				'key'     => 'start_date',
				'compare' => 'NOT EXISTS',
			],
		];
		$args = [
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'meta_query'     => $meta_query,
			'fields'         => 'ids',
			'post_status'    => 'any',
		];
		$products = get_posts( $args );
		
		if ( empty( $products ) ) {
			return false;
		}
		return $products;
	}

	/**
	 * Verwijderen van product (inclusief variaties)
	 *
	 * @param int $product_id
	 *
	 * @return mixed
	 */
	protected function task( $product_id ) {

		$product = wc_get_product( $product_id );
		if ( false == $product ) {
			return false;
		}
		$variations = $product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( false == $variation ) {
				continue;
			}
			$variation->delete( true );
		}
		$product->delete( true );
		$this->increment_processed_count();

		return false;
	}
}
