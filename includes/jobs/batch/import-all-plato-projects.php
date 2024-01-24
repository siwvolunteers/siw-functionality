<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Jobs\Update_Job;
use SIW\Plato\Database\Projects\Query;
use SIW\Plato\Database\Projects\Row;
use SIW\WooCommerce\Import\Product;

class Import_All_Plato_Projects extends Update_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	public function get_name(): string {
		return __( 'Importeer alle Plato-projecten', 'siw' );
	}

	#[\Override]
	public function start(): void {

		$query = new Query();
		$project_ids = $query->get_results( [ 'project_id' ], [], null, null );
		$project_ids = wp_list_pluck( $project_ids, 'project_id' );
		$this->enqueue_items( $project_ids, self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function import_project( string $project_id ) {
		$query = new Query();
		/** @var Row */
		$project = $query->get_item_by( 'project_id', $project_id );
		$import = new Product( $project, true );
		$import->process();
	}
}
