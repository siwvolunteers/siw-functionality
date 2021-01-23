<?php declare(strict_types=1);

namespace SIW\Newsletter;

/**
 * Hash voor bevestiging aanmelding nieuwsbrief
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Hash {
	
	/** Optienaam voor salt */
	const SALT_OPTION_NAME = 'siw_newsletter_salt';

	/** Hash-algoritme */
	const HASH_ALGORITHM = 'sha1';

	/** Lengte van password voor salt */
	const PASSWORD_LENGTH = 64;

	/** Genereert hash om later de juistheid van de data te kunnen controleren */
	public static function generate_hash( string $data ) : string {
		return hash_hmac(
			self::HASH_ALGORITHM,
			$data,
			self::get_salt()
		);
	}

	/** Controleert of data geldig is o.b.v. hash */
	public static function data_is_valid( string $data, string $hash ) : bool {
		return hash_equals(
			self::generate_hash( $data ),
			$hash
		);
	}

	/** Geeft salt terug */
	protected static function get_salt() : string {
		$salt = get_option( self::SALT_OPTION_NAME );
		if ( false === $salt ) {
			$salt = wp_generate_password( self::PASSWORD_LENGTH, true, true );
			update_option( self::SALT_OPTION_NAME, $salt );
		}
		return $salt;
	}
}
