<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * @see https://schema.org/ContactPoint
 */
class Contact_Point extends Thing {

	#[\Override]
	protected function get_type(): string {
		return 'ContactPoint';
	}

	public function set_email( string $email ): static {
		return $this->set_property( 'email', $email );
	}

	public function set_telephone( string $telephone ): static {
		return $this->set_property( 'telephone', $telephone );
	}
}
