<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use \Spatie\Enum\Enum;

/**
 * Status van evenement
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see https://schema.org/EventStatusType
 * 
 * @method static self EventScheduled()
 * @method static self EventCancelled()
 * @method static self EventMovedOnline()
 * @method static self EventPostponed()
 * @method static self EventRescheduled()
 */
class Event_Status_Type extends Enum {

	/** {@inheritDoc} */
	protected static function values() : \Closure {
		return function( string $value ): string {
			return "https://schema.org/{$value}";
		};
	}
}