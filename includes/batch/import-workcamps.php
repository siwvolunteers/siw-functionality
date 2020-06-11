<?php

namespace SIW\Batch;

use SIW\Plato\Import_Workcamps as Plato_Import_Workcamps;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Proces om Groepsprojecten te importeren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Import_Workcamps extends Job {

	/**
	 * Optie waarin geïmporteerde ids opgeslagen worden
	 * 
	 * @var int
	 */
	const IMPORTED_PROJECT_IDS_OPTION = 'siw_imported_project_ids';

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'import_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'importeren Groepsprojecten';

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
		$import = new Plato_Import_Workcamps;
		$data = $import->run();

		//Geïmporteerde ids opslaan zodat uit Plato verwijderde projecten herkend kunnen worden
		update_option( self::IMPORTED_PROJECT_IDS_OPTION, wp_list_pluck( $data, 'project_id' ) );
		return $data;
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
