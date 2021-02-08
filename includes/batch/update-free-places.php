<?php declare(strict_types=1);

namespace SIW\Batch;

use SIW\Plato\Import_FPL;

use function SIW\Plato\get_project_free_places;

/**
 * Proces om vrije plaatsen van Groepsprojecten bij te werken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Free_Places extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_free_places';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'bijwerken vrije plaatsen';

	/**
	 * {@inheritDoc}
	 */
	protected string $category = 'plato';

	/**
	 * {@inheritDoc}
	 */
	protected bool $schedule_job = false;

	/** Bepaalt of er nog vrije plaatsen op het project zijn */
	protected function has_free_places( int $free_m, int $free_f, string $no_more_from ) : string {
		return ( ( $free_m + $free_f ) > 0 && ( false === strpos( $no_more_from, 'NLD' ) ) ) ? 'yes' : 'no';
	}

	/** Haalt vrije plaatsen lijst uit Plato op */
	protected function select_data() : array {
		$import = new Import_FPL;
		return $import->run();
	}
	
	/**
	 * Werkt aantal vrije plaatsen bij
	 *
	 * @param array $item
	 *
	 * @return bool
	 */
	protected function task( $project_id ) {

		//Zoek project op basis van project_id
		$args = [
			'project_id' => $project_id,
			'return'     => 'objects',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );
	
		//Afbreken als project niet gevonden wordt.
		if ( empty( $products ) ) {
			return false;
		}
		$product = $products[0];

		$project_free_places = get_project_free_places( $project_id );
		if ( null == $project_free_places ) {
			return false;
		}

		$new_free_places = $this->has_free_places(
			$project_free_places->get_free_m(),
			$project_free_places->get_free_f(),
			$project_free_places->get_no_more_from()
		);

		if ( $new_free_places !== $product->get_meta( 'freeplaces' ) ) {
			$product->update_meta_data( 'freeplaces', $new_free_places );
			$product->save();
			$this->increment_processed_count();
		}

		return false;
	}
}
