<?php

namespace SIW\Batch;

/**
 * Proces om oude Groepsprojecten te verwijderen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Delete_Workcamps extends Job {

	/**
	 * Aantal maanden voordat Groepsproject verwijderd wordt.
	 */
	const MAX_AGE_WORKCAMP = 6;

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
	 *
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() - ( self::MAX_AGE_WORKCAMP * MONTH_IN_SECONDS ) );

		$args = [
			'return'         => 'ids',
			'limit'          => -1,
			'max_start_date' => $limit
		];
		$products = wc_get_products( $args );
		
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

		//Verwijder projectspecifieke afbeeldingen
		$project_images = get_posts([
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => [
				[
					'key'     => 'plato_project_id',
					'value'   => $product->get_meta('project_id'),
					'compare' => '='
				],
			],
		]);
		foreach ( $project_images as $project_image ) {
			wp_delete_attachment( $project_image, true );
		}

		//Verwijder alle variaties
		$variations = $product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( false == $variation ) {
				continue;
			}
			$variation->delete( true );
		}

		//Verwijder het product zelf
		$product->delete( true );
		$this->increment_processed_count();

		return false;
	}
}
