<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * @see https://schema.org/Place
 * */
class Place extends Thing {

	#[\Override]
	protected function get_type(): string {
		return 'Place';
	}

	public function set_address( Postal_Address $postal_address ): static {
		return $this->set_property( 'address', $postal_address );
	}
}
