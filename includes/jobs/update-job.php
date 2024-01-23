<?php declare(strict_types=1);

namespace SIW\Jobs;

use SIW\Attributes\Add_Action;
use SIW\Update;

abstract class Update_Job extends Batch_Job {
	#[Add_Action( Update::PLUGIN_UPDATED_HOOK )]
	public function maybe_start_job() {
		$this->start();
		// translators: %s is de naam van de actie
		return sprintf( __( 'Actie gestart: %s', 'siw' ), $this->get_name() );
	}
}
