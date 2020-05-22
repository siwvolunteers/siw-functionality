<?php

namespace SIW\Batch;

use SIW\Util;

/**
 * Proces om tarieven van Groepsprojecten bij te werken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Workcamp_Tariffs extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamp_tariffs';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken tarieven';
	
	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer alle zichtbare projecten
	 *
	 * @return array
	 */
	protected function select_data() {
		$args = [
			'return'     => 'ids',
			'limit'      => -1,
		];
		return wc_get_products( $args );
	}

	/**
	 * Werk tarieven van het groepsproject bij
	 *
	 * @param int $product_id
	 *
	 * @return mixed
	 */
	protected function task( $product_id ) {
		
		$product = wc_get_product( $product_id );
		
		/* Afbreken als product niet meer bestaat */
		if ( ! $product instanceof \WC_Product ) {
			return false;
		}
	
		//Afbreken als product een afwijkend tarief heeft
		if ( $product->get_meta( 'has_custom_tariff' ) ) {
			return;
		}

		$tariffs = siw_get_data( 'workcamps/tariffs' );
		$sale = Util::is_workcamp_sale_active();

		$workcamp_sale = siw_get_option( 'workcamp_sale' );
		$variations = $product->get_children();

		$is_updated = false;

		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$variation_tariff = $variation->get_attributes()['pa_tarief'];
			$tariff = $tariffs[ $variation_tariff ] ?? 'regulier';

			$regular_price = $tariff['regular_price'];
			$sale_price = $tariff['sale_price'];

			$variation->set_props([
				'regular_price'     => $regular_price,
				'sale_price'        => $sale ? $sale_price : null,
				'price'             => $sale ? $sale_price : $regular_price,
				'date_on_sale_from' => $sale ? date( 'Y-m-d 00:00:00', strtotime( $workcamp_sale['start_date'] ) ) : null,
				'date_on_sale_to'   => $sale ? date( 'Y-m-d 23:59:59', strtotime( $workcamp_sale['end_date'] ) ) : null,
			]);
			if ( ! empty( $variation->get_changes() ) ) {
				$variation->save();
				$is_updated = true;
			}
		}
		if ( $is_updated ) {
			$this->increment_processed_count();
		}
		return false;
	}

}
