<?php declare(strict_types=1);

namespace SIW;

/**
 * Functionaliteit t.b.v. meertaligheid
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class I18n {

	/** Geeft vertaalde permalink in meegegeven taal terug */
	public static function get_translated_permalink( string $permalink, string $language_code ): string {
		return apply_filters( 'wpml_permalink', $permalink, $language_code, true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft terug of de huidige taal gelijk is aan de standaardtaal */
	public static function is_default_language(): bool {
		return ( self::get_current_language() === self::get_default_language() );
	}

	/** Geeft code van huidige taal terug */
	public static function get_current_language(): string {
		return apply_filters( 'wpml_current_language', get_bloginfo( 'language' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft code van standaardtaal terug */
	public static function get_default_language(): string {
		return apply_filters( 'wpml_default_language', get_bloginfo( 'language' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
