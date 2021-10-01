<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Data\Plato\Project;
use SIW\Interfaces\Actions\Async as Async_Action_Interface;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Importeren projecten uit Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Project implements Async_Action_Interface {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'import_plato_project';
	}

	/** {@inheritDoc} */
	public function get_name() : string {
		return __( 'Importeren project uit Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_argument_count(): int {
		return 1;
	}

	/** {@inheritDoc} */
	public function process( $project_id = '' ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( ! is_a( $plato_project, Project::class ) ) {
			return;
		}

		$import = new Import_Product( $plato_project, true );
		$import->process();
	}
}
