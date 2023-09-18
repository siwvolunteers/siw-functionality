<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

use SIW\Data\Database_Table;
use SIW\Helpers\Database;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Proces om alle Plato-project opnieuw te importeren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Import_All_Plato_Projects implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'import_all_plato_projects';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeer alle Plato-projecten', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		$database = new Database( Database_Table::PLATO_PROJECTS() );
		return $database->get_col( 'project_id' );
	}

	/** {@inheritDoc} */
	public function process( $project_id ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( null === $plato_project ) {
			return;
		}
		$import = new Import_Product( $plato_project, true );
		$import->process();
	}
}
