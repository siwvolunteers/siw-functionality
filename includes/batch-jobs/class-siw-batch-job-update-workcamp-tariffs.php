<?php

/**
 * Proces om tarieven van Groepsprojecten bij te werken
 * 
 * @package   SIW\Batch
 * @author    Maarten Bruna
 * @copyright 2017-2019 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Util
 * @uses      SIW_Properties
 */
class SIW_Batch_Job_Update_Workcamp_Tariffs extends SIW_Batch_Job {

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
		$products = wc_get_products( $args );
		
		return $products;
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
		if ( false == $product ) {
			return false;
		}
	
		$tariffs = $this->get_tariffs();
		$sale = SIW_Util::is_workcamp_sale_active();

		$workcamp_sale = siw_get_option( 'workcamp_sale' );
		$variations = $product->get_children();

		$updated = false;

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
				$updated = true;
			}
		}
		if ( true === $updated ) {
			$this->increment_processed_count();
		}
		return false;
	}

	/**
	 * Geeft tarieven terug
	 * 
	 * @return array
	 */
	public function get_tariffs() {
		$tariffs = [
			'regulier' => [
				'name'          => 'regulier',
				'regular_price' => SIW_Properties::WORKCAMP_FEE_REGULAR,
				'sale_price'    => SIW_Properties::WORKCAMP_FEE_REGULAR_SALE
			],
			'student' => [
				'name'          => 'student / <18',
				'regular_price' => SIW_Properties::WORKCAMP_FEE_STUDENT,
				'sale_price'    => SIW_Properties::WORKCAMP_FEE_STUDENT_SALE
			]
		];
		return $tariffs;
	}
}
