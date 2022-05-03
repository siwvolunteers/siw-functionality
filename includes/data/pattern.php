<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * WooCommerce taxonomy attributes
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self DATE()
 * @method static self POSTCODE()
 * @method static self HOUSENUMBER()
 * @method static self LATITUDE()
 * @method static self LONGITUDE()
 * @method static self IP()
 * @method static self EMAIL_LOCAL_PART()
 */
class Pattern extends Enum {

	/** {@inheritDoc} */
	protected static function values(): array {
		return [
			'DATE'             => '^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$',
			'POSTCODE'         => '^[1-9][0-9]{3}\s?[a-zA-Z]{2}$',
			'HOUSENUMBER'      => '^(\d+)\s*[\s\w\-\/]*$',
			'LATITUDE'         => '^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$',
			'LONGITUDE'        => '^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$',
			'IP'               => '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$',
			'EMAIL_LOCAL_PART' => '^[^\s@]+$',
		];
	}
}
