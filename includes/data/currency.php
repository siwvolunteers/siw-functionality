<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\External\Exchange_Rates;

/**
 * Bevat informatie over een valuta
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Currency {

	/**
	 * ISO-code
	 */
	protected string $iso_code;

	/**
	 * Naam
	 */
	protected string $name;

	/**
	 * Valuta-teken
	 */
	protected string $symbol;

	/**
	 * @param array $data
	 */
	public function __construct( array $currency ) {
		$defaults = [
			'iso'    => '',
			'name'   => '',
			'symbol' => '',
		];
		$currency = wp_parse_args( $currency, $defaults );

		$this->iso_code = $currency['iso'];
		$this->name = $currency['name'];
		$this->symbol = $currency['symbol'];
	}

	/**
	 * Geeft de ISO-code van de valuta terug
	 *
	 * @return string
	 */
	public function get_iso_code() : string {
		return $this->iso_code;
	}

	/**
	 * Geeft de naam van de valuta terug
	 *
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft het symbool van de valuta terug
	 *
	 * @return string
	 */
	public function get_symbol() : string {
		return $this->symbol;
	}

	/**
	 * Geeft wisselkoers van huidige valuta naar Euro terug
	 *
	 * @return float|null
	 */
	public function get_exchange_rate() : ?float {
		$exchange_rates = new Exchange_Rates();
		return $exchange_rates->get_rate( $this->iso_code );
	}

	/**
	 * Rekent bedrag in huidige valuta om naar Euro
	 *
	 * @param float $amount
	 * @param int $decimals
	 *
	 * @return string|null
	 */
	public function convert_to_euro( float $amount, int $decimals = 0 ) : ?string {
		$exchange_rate = $this->get_exchange_rate();
		if ( is_null( $exchange_rate ) ) {
			return null;
		}
		$amount_in_euro = (float) $amount * (float) $exchange_rate;
		return number_format_i18n( $amount_in_euro, $decimals );
	}
}
