<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/** @see https://schema.org/VirtualLocation */
class Virtual_Location extends Thing {
	#[\Override]
	public function get_type(): string {
		return 'VirtualLocation';
	}
}
