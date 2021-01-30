<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use \Spatie\Enum\Enum;

/**
 * Type evenement (offline, online of allebei)
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/eventAttendanceMode
 * 
 * @method static self MixedEventAttendanceMode()
 * @method static self OfflineEventAttendanceMode()
 * @method static self OnlineEventAttendanceMode()
 */
class Event_Attendance_Mode extends Enum {

	/** {@inheritDoc} */
	protected static function values() : \Closure {
		return function( string $value ): string {
			return "https://schema.org/{$value}";
		};
	}
}