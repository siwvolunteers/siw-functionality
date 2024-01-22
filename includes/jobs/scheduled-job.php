<?php declare(strict_types=1);

namespace SIW\Jobs;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;

abstract class Scheduled_Job extends Batch_Job {

	public const START_HOOK = 'siw_start_jobs';

	abstract protected function get_frequency(): Job_Frequency;

	#[Add_Action( self::START_HOOK )]
	public function maybe_start_job( string $frequency ) {
		if ( Job_Frequency::tryFrom( $frequency ) !== $this->get_frequency() ) {
			return;
		}
		$this->start();
		// translators: %s is de naam van de actie
		return sprintf( __( 'Actie gestart: %s', 'siw' ), $this->get_name() );
	}
}
