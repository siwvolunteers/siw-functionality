<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;
use SIW\Plato\Import_FPL;
use SIW\WooCommerce\Import\Free_Places as Import_Free_Places;

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
		$import = new Import_FPL();
		$this->enqueue_items( $import->run(), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function importproject_fpl( string $project_id ) {

		$project_free_places = siw_get_plato_project_free_places( $project_id );
		if ( null === $project_free_places ) {
			return;
		}

		$import = new Import_Free_Places( $project_free_places );
		$import->process();
	}
}
