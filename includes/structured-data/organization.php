<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Organisatie
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/Organization
 */
class Organization extends Thing {

	/** {@inheritDoc} */
	protected function get_type(): string {
		return 'Organization';
	}

	/** Zet logo */
	public function set_logo( string $logo ): static {
		return $this->set_property( 'logo', $logo );
	}

	/** Zet non-profit-status */
	public function set_non_profit_status( Non_Profit_Type $non_profit_status ): static {
		return $this->set_property( 'nonprofitStatus', $non_profit_status );
	}

}
