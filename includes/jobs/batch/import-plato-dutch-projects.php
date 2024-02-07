<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;
use SIW\Plato\Import_Dutch_Workcamps as Plato_Import_Dutch_Workcamps;
use SIW\WooCommerce\Import\Product as Import_Product;

class Import_Plato_Dutch_Projects extends Scheduled_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::DAILY;
	}

	#[\Override]
	public function get_name(): string {
		return __( 'Importeren projecten uit Plato', 'siw' );
	}

	#[\Override]
	public function start(): void {
		$import = new Plato_Import_Dutch_Workcamps();
		$this->enqueue_items( $import->run(), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function import_project( string $project_id ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( null === $plato_project ) {
			return;
		}
		$import = new Import_Product( $plato_project );
		$import->process();
	}
}
