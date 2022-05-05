<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\External\Exchange_Rates;

/**
 * Bevat informatie over een valuta
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Currency extends Data {

	/** ISO-code */
	protected string $iso_code;

	/*** Naam */
	protected string $name;

	/** Valuta-teken */
	protected string $symbol;

	/** Geeft de ISO-code van de valuta terug */
	public function get_iso_code() : string {
		return $this->iso_code;
	}

	/** Geeft de naam van de valuta terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft het symbool van de valuta terug */
	public function get_symbol() : string {
		return $this->symbol;
	}
}
