<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration;

/**
 * @see https://schema.org/eventAttendanceMode
 */
enum Event_Attendance_Mode: string implements Enumeration {
	case MIXED = 'MixedEventAttendanceMode';
	case OFFLINE = 'OfflineEventAttendanceMode';
	case ONLINE = 'OnlineEventAttendanceMode';
}
