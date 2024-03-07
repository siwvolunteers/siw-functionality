<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;
use SIW\Plato\Database\Projects\Query;
use SIW\Plato\Import\Partner_Projects;
use SIW\Plato\Import\Partners;

use SIW\Plato\Database\Partners\Table as Partners_Table;
use SIW\Plato\Database\Projects\Table as Projects_Table;
use SIW\WooCommerce\Import\Product;

class Import_Projects extends Scheduled_Job {

	private const ACTION_HOOK = self::class;
	private const IMPORT_PROJECT_ACTION_HOOK = self::class . '\Import_Project';

	/** {@inheritDoc} */
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::DAILY;
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Importeren projecten', 'siw' );
	}

	/** {@inheritDoc} */
	public function start(): void {
		( new Partners_Table() )->delete_all();
		( new Projects_Table() )->delete_all();

		$import = new Partners();
		$this->enqueue_items( $import->run(), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function import_partner_projects( string $technical_key ) {
		$import = new Partner_Projects( $technical_key );
		$this->enqueue_items( $import->run(), self::IMPORT_PROJECT_ACTION_HOOK );
	}

	#[Add_Action( self::IMPORT_PROJECT_ACTION_HOOK )]
	public function import_partner_project( string $project_id ) {
		$query = new Query();
		$project = $query->get_item_by( 'project_id', $project_id );

		if ( false === $project ) {
			return;
		}

		$import = new Product( $project, true );
		$import->process();
	}
}
