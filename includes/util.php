<?php declare(strict_types=1);

namespace SIW;

/**
 * Hulpfuncties
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Util {

	/** Geeft array met pagina's in standaardtaal terug */
	public static function get_pages(): array {
		$default_lang = I18n::get_default_language();
		$current_lang = I18n::get_current_language();
		do_action( 'wpml_switch_language', $default_lang ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$results = get_pages();
		do_action( 'wpml_switch_language', $current_lang ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		$pages = [];
		foreach ( $results as $result ) {
			$ancestors = array_reverse( get_ancestors( $result->ID, 'page' ) );
			$callback = function( &$value ) {
				$value = get_the_title( $value );
			};
			array_walk( $ancestors, $callback );
			$prefix = ! empty( $ancestors ) ? implode( '/', $ancestors ) . '/' : '';
			$pages[ $result->ID ] = esc_html( $prefix . $result->post_title );
		}
		return $pages;
	}

	/** Berekent leeftijd in jaren o.b.v. huidige datum */
	public static function calculate_age( string $date ): int {
		$from = new \DateTime( $date );
		$to   = new \DateTime( 'today' );
		return $from->diff( $to )->y;
	}

	/** Creëert term indien deze nog niet bestaat */
	public static function maybe_create_term( string $taxonomy, string $slug, string $name, $order = null ): int|bool {
		$term = get_term_by( 'slug', $slug, $taxonomy );

		// Als term al bestaat zijn we snel klaar
		if ( is_a( $term, \WP_Term::class ) ) {
			return $term->term_id;
		}

		// Anders nieuwe term aanmaken
		$new_term = wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
		if ( is_wp_error( $new_term ) ) {
			return false;
		}

		// Eventueel volgorde zetten
		if ( ! empty( $order ) ) {
			update_term_meta( $new_term['term_id'], 'order', $order );
		}

		return $new_term['term_id'];
	}

	/** Geeft aan of het een productieomgeving betreft */
	public static function is_production(): bool {
		return 'production' === \wp_get_environment_type();
	}
}
