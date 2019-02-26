<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om tarieven van Groepsprojecten bij te werken
 * 
 * @package   SIW\Background-Process
 * @author    Maarten Bruna
 * @copyright 2017-2019 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Util
 */
class SIW_Update_Workcamp_Tariffs extends SIW_Background_Process {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamp_tariffs_process';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken tarieven';

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

		$product = wc_get_product( $product_id );
	
		/* Afbreken als product niet meer bestaat */
		if ( false == $product ) {
			return false;
		}
	
		$sale = SIW_Util::is_workcamp_sale_active();

		$variations = $product->get_children();
	
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$variation_tariff = $variation->get_attributes()['pa_tarief'];
			$tariff = $tariffs[ $variation_tariff ];

			$regular_price = $tariff['regular_price'];
			$sale_price = $tariff['sale_price'];

			$variation->set_props([
				'regular_price'     => $regular_price,
				'sale_price'        => $sale ? $sale_price : null,
				'price'             => $sale ? $sale_price : $regular_price,
				'date_on_sale_from' => $sale ? date( DATE_ISO8601, strtotime( siw_get_setting( 'workcamp_sale_start_date' ) ) ) : null,
				'date_on_sale_to'   => $sale ? date( DATE_ISO8601, strtotime( siw_get_setting( 'workcamp_sale_end_date' ) ) ) : null,
			]);
			$variation->save();
		}
		$this->increment_processed_count();
		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'workcamps' => [ 'title' => __( 'Groepsprojecten', 'siw' ) ]	];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Bijwerken tarieven', 'siw' ) ];
	siw_register_background_process( 'SIW_Update_Workcamp_Tariffs', 'update_workcamp_tariffs', $node, $parent_nodes, true );
} );

