<?php

/**
 * Proces om vrije plaatsen van Groepsprojecten bij te werken
 * 
 * @package   SIW\Batch
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Plato_Import_FPL
 */
class SIW_Batch_Job_Update_Free_Places extends SIW_Batch_Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_free_places';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken vrije plaatsen';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'plato';

	/**
	 * {@inheritDoc}
	 */
	protected $schedule_job = false;

	/**
	 * Bepaalt of er nog vrije plaatsen op het project zijn
	 *
	 * @param int $free_m
	 * @param int $free_f
	 * @param string $no_more_from
	 * @return string
	 */
	protected function has_free_places( $free_m, $free_f, $no_more_from ) {
		$free_m = (int) $free_m;
		$free_f = (int) $free_f;
	
		return ( ( $free_m + $free_f ) > 0 && ( false === strpos( $no_more_from, 'NLD' ) ) ) ? 'yes' : 'no';
	}

	/**
	 * Haalt vrije plaatsen lijst uit Plato op
	 * 
	 * @return array
	 */
	protected function select_data() {
		$import = new SIW_Plato_Import_FPL;
		$data = $import->run();
		
		return $data;
	}
	
	/**
	 * Werkt aantal vrije plaatsen bij
	 *
	 * @param array $item
	 *
	 * @return bool
	 */
	protected function task( $item ) {

		//Zoek project op basis van project_id
		$args = [
			'visibility' => 'visible',
			'project_id' => $item['project_id'],
			'return'     => 'objects',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );
	
		//Afbreken als project niet gevonden wordt.
		if ( empty( $products ) ) {
			return false;
		}
		$product = $products[0];

		$new_free_places = $this->has_free_places( $item['free_m'], $item['free_f'], $item['no_more_from'] );


		if ( $new_free_places !== $product->get_meta( 'freeplaces' ) ) {
			$product->update_meta_data( 'freeplaces', $new_free_places );
			$product->save();
			$this->increment_processed_count();
		}

		return false;
	}
}
