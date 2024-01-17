<?php declare(strict_types=1);

namespace SIW\Util;

class I18n {

	public static function get_translated_permalink( string $permalink, string $language_code ): string {
		return apply_filters( 'wpml_permalink', $permalink, $language_code, true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	public static function is_default_language(): bool {
		return ( self::get_current_language() === self::get_default_language() );
	}

	public static function get_current_language(): string {
		return apply_filters( 'wpml_current_language', get_bloginfo( 'language' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	public static function get_default_language(): string {
		return apply_filters( 'wpml_default_language', get_bloginfo( 'language' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
