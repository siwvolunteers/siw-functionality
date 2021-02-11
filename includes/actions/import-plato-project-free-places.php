<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Interfaces\Actions\Action as Action_Interface;
use SIW\Plato\Import_FPL;
use SIW\WooCommerce\Import\Free_Places as Import_Free_Places;

use function SIW\Plato\get_project_free_places;

/**
 * Importeren vrije plaatsen per project uit Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Project_Free_Places implements Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'import_plato_project_free_places';
	}
	
	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeren vrije plaatsen uit Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function select_data() : array {
		$import = new Import_FPL;
		return $import->run();
	}
	
	/** {@inheritDoc} */
	public function process( $project_id ) {

		$project_free_places = get_project_free_places( $project_id );
		if ( null == $project_free_places ) {
			return;
		}

		$import = new Import_Free_Places( $project_free_places );
		$import->process();
		return;
	}
}
