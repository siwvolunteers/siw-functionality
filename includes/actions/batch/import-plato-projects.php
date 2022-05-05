<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\Plato\Import_Workcamps as Plato_Import_Workcamps;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Importeren projecten uit Plato
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Projects implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'import_plato_projects';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeren projecten uit Plato', 'siw' );
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
	public function select_data(): array {
		$import = new Plato_Import_Workcamps();
		return $import->run();
	}

	/** {@inheritDoc} */
	public function process( $project_id ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( null === $plato_project ) {
			return;
		}
		$import = new Import_Product( $plato_project );
		$import->process();
	}
}
