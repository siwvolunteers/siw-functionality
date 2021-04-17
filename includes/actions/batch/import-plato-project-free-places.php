<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\Plato\Import_FPL;
use SIW\WooCommerce\Import\Free_Places as Import_Free_Places;

/**
 * Importeren vrije plaatsen per project uit Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Project_Free_Places implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'import_plato_project_free_places';
	}
	
	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeren vrije plaatsen uit Plato', 'siw' );
	}
	
	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function select_data() : array {
		$import = new Import_FPL;
		return $import->run();
	}
	
	/** {@inheritDoc} */
	public function process( $project_id ) {

		$project_free_places = siw_get_plato_project_free_places( $project_id );
		if ( null == $project_free_places ) {
			return;
		}

		$import = new Import_Free_Places( $project_free_places );
		$import->process();
		return;
	}
}
