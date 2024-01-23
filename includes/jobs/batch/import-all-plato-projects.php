<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Database_Table;
use SIW\Helpers\Database;
use SIW\Jobs\Update_Job;
use SIW\WooCommerce\Import\Product as Import_Product;

class Import_All_Plato_Projects extends Update_Job {

	private const ACTION_HOOK = self::class;

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeer alle Plato-projecten', 'siw' );
	}

	/** {@inheritDoc} */
	public function start(): void {
		$database = new Database( Database_Table::PLATO_PROJECTS );
		$this->enqueue_items( $database->get_col( 'project_id' ), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function import_project( string $project_id ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( null === $plato_project ) {
			return;
		}
		$import = new Import_Product( $plato_project, true );
		$import->process();
	}
}
