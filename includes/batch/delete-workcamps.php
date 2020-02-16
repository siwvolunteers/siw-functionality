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

		// Verwijder afbeelding als dit een projectspecifieke afbeelding uit Plato is
		if ( $product->get_meta( 'has_plato_image', true ) ) {
			wp_delete_attachment( $product->get_image_id(), true );
		}

		// Verwijder alle variaties
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
