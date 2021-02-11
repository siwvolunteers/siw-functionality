<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Interfaces\Actions\Action as Action_Interface;
use SIW\Plato\Import_Workcamps as Plato_Import_Workcamps;
use SIW\WooCommerce\Import\Product as Import_Product;

use function SIW\Plato\get_project;

/**
 * Importeren projecten uit Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Projects implements Action_Interface {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'import_plato_projects';
	}

	/** {@inheritDoc} */
	public function get_name() : string {
		return __( 'Importeren projecten uit Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function select_data() : array {
		$import = new Plato_Import_Workcamps;
		return $import->run();
	}

	/** {@inheritDoc} */
	public function process( $project_id ) {
		$plato_project = get_project( $project_id );
		$import = new Import_Product( $plato_project );
		$import->process();
	}
}
