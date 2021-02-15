<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Adres
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/PostalAddress
 */
class Postal_Address extends Thing {

	/** {@inheritDoc} */
	protected function get_type() : string {
		return 'PostalAddress';
	}

	/** Zet adres (straat + huisnummer) */
	public function set_street_address( string $street_address ) {
		return $this->set_property( 'streetAddress', $street_address );
	}

	/** Zet plaats */
	public function set_address_locality( string $address_locality ) {
		return $this->set_property( 'addressLocality', $address_locality );
	}

	/** Zet postcode */
	public function set_postal_code( string $postal_code ) {
		return $this->set_property( 'postalCode', $postal_code );
	}

	/** Zet regio (land) */
	public function set_address_region( string $address_region ) {
		return $this->set_property( 'addressRegion', $address_region );
	}
	
	/** Zet land */
	public function set_address_country( string $address_country ) {
		return $this->set_property( 'addressCountry', $address_country );
	}
}
