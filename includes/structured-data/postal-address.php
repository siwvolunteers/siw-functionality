<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/** @see https://schema.org/PostalAddress */
class Postal_Address extends Thing {

	#[\Override]
	protected function get_type(): string {
		return 'PostalAddress';
	}

	public function set_street_address( string $street_address ): static {
		return $this->set_property( 'streetAddress', $street_address );
	}

	public function set_address_locality( string $address_locality ): static {
		return $this->set_property( 'addressLocality', $address_locality );
	}

	public function set_postal_code( string $postal_code ): static {
		return $this->set_property( 'postalCode', $postal_code );
	}

	public function set_address_region( string $address_region ): static {
		return $this->set_property( 'addressRegion', $address_region );
	}

	public function set_address_country( string $address_country ): static {
		return $this->set_property( 'addressCountry', $address_country );
	}
}
