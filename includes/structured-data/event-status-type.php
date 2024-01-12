<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration;

/**
 * @see https://schema.org/EventStatusType
 */
enum Event_Status_Type: string implements Enumeration {
	case SCHEDULED = 'EventScheduled';
	case CANCELLED = 'EventCancelled';
	case MOVED_LINE = 'EventMovedOnline';
	case POSTPONED = 'EventPostponed';
	case RESCHEDULED = 'EventRescheduled';
}
