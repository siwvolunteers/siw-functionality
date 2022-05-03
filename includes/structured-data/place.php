<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Plaats
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/Place
 */
class Place extends Thing {

	/** @inheritDoc */
	protected function get_type() : string {
		return 'Place';
	}

	/** Zet adres */
	public function set_address( Postal_Address $postal_address ) {
		return $this->set_property( 'address', $postal_address );
	}

}
