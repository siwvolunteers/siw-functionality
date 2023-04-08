<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration as I_Enumeration;

/**
 * Type evenement (offline, online of allebei)
 *
 * @copyright 2021-2023 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/eventAttendanceMode
 */
enum Event_Attendance_Mode implements I_Enumeration {
	case MixedEventAttendanceMode;
	case OfflineEventAttendanceMode;
	case OnlineEventAttendanceMode;
}
