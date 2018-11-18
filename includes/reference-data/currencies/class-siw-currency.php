<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een valuta
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Currency {

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
	public function __construct( $currency ) {
		$defaults = [
			'iso'       => '',
			'name'		=> '',
			'symbol'    => '',
		];
		$currency = wp_parse_args( $currency, $defaults );

		$this->set_iso_code( $currency['iso'] );
		$this->set_name( $currency['name'] );
		$this->set_symbol( $currency[ 'symbol'] );
	}

	/**
	 * Zet de ISO-code van de valuta
	 *
	 * @param string $iso_code
	 * @return void
	 */
	public function set_iso_code( $iso_code ) {
		$this->iso_code = $iso_code;
		return $this;
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
	 * Zet de naam van de valuta
	 *
	 * @param string $name
	 * @return void
	 */
	public function set_name( $name ) {
		$this->name = $name;
		return $this;
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
	 * Zet het symbool van de valuta
	 *
	 * @param string $symbol
	 * @return $this
	 */
	public function set_symbol( $symbol ) {
		$this->symbol = $symbol;
		return $this;
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
		$exchange_rates = siw_get_exchange_rates();
		$exchange_rate = isset( $exchange_rates[ $this->iso_code ] ) ? $exchange_rates[ $this->iso_code ] : false;
	
		return $exchange_rate;
	}

	/**
	 * Rekent bedrag in huidige valuta om naar Euro
	 *
	 * @param float $amount
	 * @return float
	 */
	public function convert_to_euro( $amount, $decimals = 0 ) {
		$exchange_rate = $this->get_exchange_rate();
		if ( false == $exchange_rate ) {
			return false;
		}
	
		$amount_in_euro = (float) $amount * (float) $exchange_rate;
		$amount_in_euro = number_format( $amount_in_euro, $decimals, ',', '.' );
		return $amount_in_euro;
	}
}