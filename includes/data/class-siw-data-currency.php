<?php

/**
 * Bevat informatie over een valuta
 * 
 * @package   SIW\Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Data_Currency {

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
		$external_exchange_rates = new SIW_External_Exchange_Rates();
		$exchange_rates = $external_exchange_rates->get_rates();
		$exchange_rate = $exchange_rates[ $this->iso_code ] ?? false;
	
		return $exchange_rate;
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
		$amount_in_euro = number_format_i18n( $amount_in_euro, $decimals );
		return $amount_in_euro;
	}
}