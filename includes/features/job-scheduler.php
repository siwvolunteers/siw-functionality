<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Job_Frequency;
use SIW\Data\Job_Time;
use SIW\Jobs\Scheduled_Job;
use SIW\Update;

class Job_Scheduler extends Base {

	private const ACTION_GROUP = 'siw_jobs';
	private const START_TIME = '0:00';

	#[Add_Action( Update::PLUGIN_UPDATED_HOOK )]
	public function schedule_actions() {

		$scheduled_actions = as_get_scheduled_actions(
			[
				'group'    => self::ACTION_GROUP,
				'status'   => \ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
			]
		);
		array_walk(
			$scheduled_actions,
			fn( \ActionScheduler_Action $action ) => as_unschedule_all_actions( $action->get_hook() )
		);

		$job_frequencies = Job_Frequency::cases();
		array_walk(
			$job_frequencies,
			fn( Job_Frequency $frequency ) => as_schedule_recurring_action(
				strtotime( 'tomorrow ' . self::START_TIME . wp_timezone_string() ),
				$frequency->interval(),
				Scheduled_Job::START_HOOK,
				[ 'frequency' => $frequency->value ],
				self::ACTION_GROUP
			)
		);
	}
}
