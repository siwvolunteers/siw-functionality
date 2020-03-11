<?php

namespace SIW\Batch;

use SIW\Plato\Import_Workcamps;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Workcamps extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken Groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'plato';

	/**
	 * {@inheritDoc}
	 */
	protected $schedule_job = false;

	/**
	 * {@inheritDoc}
	 */
	protected $batch_size = 50;

	/**
	 * Haal Groepsprojecten op uit Plato
	 *
	 * @return array
	 */
	 protected function select_data() {
		$import = new Import_Workcamps;
		return $import->run();
	}

	/**
	 * Werk Groepsproject bij
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		$import = new Import_Product( $item );
		$result = $import->process();

		if ( true == $result ) {
			$this->increment_processed_count();
		}

		return false;
	}

	/**
	 * Extra acties bij afronden batch job
	 */
	protected function complete() {
		if ( siw_get_option( 'plato_force_full_update' ) ) {
			siw_set_option( 'plato_force_full_update', null );
		}
		parent::complete();
	}

}
