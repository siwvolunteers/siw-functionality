<?php declare(strict_types=1);

namespace SIW\Data;

enum Job_Frequency: string {
	case HOURLY = 'siw_hourly_jobs';
	case TWICE_DAILY = 'siw_twice_daily_jobs';
	case DAILY = 'siw_daily_jobs';
	case WEEKLY = 'siw_weekly_jobs';

	public function interval(): int {
		return match ( $this ) {
			self::HOURLY      => HOUR_IN_SECONDS,
			self::TWICE_DAILY => 12 * HOUR_IN_SECONDS,
			self::DAILY       => DAY_IN_SECONDS,
			self::WEEKLY      => WEEK_IN_SECONDS,
		};
	}
}
