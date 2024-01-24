<?php declare(strict_types=1);

namespace SIW;

class Util {

	public static function calculate_age( string $date ): int {
		$from = new \DateTime( $date );
		$to   = new \DateTime( 'today' );
		return $from->diff( $to )->y;
	}

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
}
