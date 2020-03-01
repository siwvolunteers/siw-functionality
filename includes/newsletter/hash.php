<?php

namespace SIW\Newsletter;

/**
 * Hash voor bevestiging aanmelding nieuwsbrief
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Hash {
	
	/**
	 * Optienaam voor salt
	 * 
	 * @var string
	 */
	const SALT_OPTION_NAME = 'siw_newsletter_salt';

	/**
	 * Hash-algoritme
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM = 'sha1';

	/**
	 * Lengte van password voor salt
	 * 
	 * @var int
	 */
	const PASSWORD_LENGTH = 64;

	/**
	 * Genereert hash om later de juistheid van de data te kunnen controleren
	 *
	 * @param string $data
	 *
	 * @return string
	 */
	public static function generate_hash( string $data ) {
		return hash_hmac(
			self::HASH_ALGORITHM,
			$data,
			self::get_salt()
		);
	}

	/**
	 * Controleert of data geldig is o.b.v. hash
	 *
	 * @param array $data
	 * @param string $hash
	 *
	 * @return bool
	 */
	public static function data_is_valid( string $data, string $hash ) {
		return hash_equals(
			self::generate_hash( $data ),
			$hash
		);
	}

	/**
	 * Geeft salt terug
	 * 
	 * @return string
	 */
	protected static function get_salt() {
		$salt = get_option( self::SALT_OPTION_NAME );
		if ( false === $salt ) {
			$salt = wp_generate_password( self::PASSWORD_LENGTH, true, true );
			update_option( self::SALT_OPTION_NAME, $salt );
		}
		return $salt;
	}
}
