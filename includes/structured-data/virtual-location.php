<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Virtuele locatie
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/VirtualLocation
 */
class Virtual_Location extends Thing {
	/** {@inheritDoc} */
	public function get_type() : string {
		return 'VirtualLocation';
	}
}
