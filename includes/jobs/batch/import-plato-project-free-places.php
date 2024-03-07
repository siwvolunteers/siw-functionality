<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;
use SIW\Plato\Database\Free_Places\Query;
use SIW\Plato\Database\Free_Places\Row;
use SIW\Plato\Database\Free_Places\Table as Free_Places_Table;
use SIW\Plato\Import\Free_Places as Free_Places_Import;
use SIW\WooCommerce\Import\Free_Places;

class Import_Plato_Project_Free_Places extends Scheduled_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::TWICE_DAILY;
	}

	#[\Override]
	public function get_name(): string {
		return __( 'Importeren vrije plaatsen uit Plato', 'siw' );
	}

	#[\Override]
	public function start(): void {
		( new Free_Places_Table() )->delete_all();

		$import = new Free_Places_Import();
		$this->enqueue_items( $import->run(), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function importproject_fpl( string $project_id ) {
		$query = new Query();
		/** @var Row */
		$project = $query->get_item_by( 'project_id', $project_id );

		if ( false === $project ) {
			return;
		}

		$import = new Free_Places( $project );
		$import->process();
	}
}
