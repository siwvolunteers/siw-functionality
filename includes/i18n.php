<?php declare(strict_types=1);

namespace SIW;

/**
 * Functionaliteit t.b.v. meertaligheid
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class i18n {

	/** Zoekt url van vertaalde pagina op basis van id */
	public static function get_translated_page_url( int $page_id ): string {
		$translated_page_id = self::get_translated_page_id( $page_id );
		return get_page_link( $translated_page_id );
	}

	/** Zoekt id van vertaalde pagina op basis van id */
	public static function get_translated_page_id( int $page_id ): int {
		return apply_filters( 'wpml_object_id', $page_id, 'page', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft vertaalde permalink in meegegeven taal terug */
	public static function get_translated_permalink( string $permalink, string $language_code ): string {
		return apply_filters( 'wpml_permalink', $permalink, $language_code ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft terug of de huidige taal gelijk is aan de standaardtaal */
	public static function is_default_language(): bool {
		return ( self::get_current_language() === self::get_default_language() );
	}

	/** Geeft code van huidige taal terug */
	public static function get_current_language(): string {
		return apply_filters( 'wpml_current_language', null ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft code van standaardtaal terug */
	public static function get_default_language(): string {
		return apply_filters( 'wpml_default_language', null ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/** Geeft gegevens van actieve talen terug */
	public static function get_active_languages(): array {
		return apply_filters( 'wpml_active_languages', null ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}

