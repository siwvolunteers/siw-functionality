<?php

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
	 *
	 * @var string
	 */
	protected $iso_code;

	/**
	 * Naam
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Valuta-teken
	 *
	 * @var string
	 */
	protected $symbol;

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
	public function get_iso_code() {
		return $this->iso_code;
	}

	/**
	 * Geeft de naam van de valuta terug
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Geeft het symbool van de valuta terug
	 *
	 * @return string
	 */
	public function get_symbol() {
		return $this->symbol;
	}

	/**
	 * Geeft wisselkoers van huidige valuta naar Euro terug
	 *
	 * @return float
	 */
	public function get_exchange_rate() {
		$exchange_rates = new Exchange_Rates();
		return $exchange_rates->get_rate( $this->iso_code );
	}

	/**
	 * Rekent bedrag in huidige valuta om naar Euro
	 *
	 * @param float $amount
	 * @param int $decimals
	 * @return float
	 */
	public function convert_to_euro( float $amount, int $decimals = 0 ) {
		$exchange_rate = $this->get_exchange_rate();
		if ( false == $exchange_rate ) {
			return false;
		}
	
		$amount_in_euro = (float) $amount * (float) $exchange_rate;
		return number_format_i18n( $amount_in_euro, $decimals );
	}
}
