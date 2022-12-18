<?php declare(strict_types=1);

use luizbills\CSS_Generator\Generator;

if ( ! function_exists( 'get_query_arg' ) ) {
	/**
	 * Haalt argument uit query
	 *
	 * @see https://core.trac.wordpress.org/ticket/34699
	 */
	function get_query_arg( $key, $query = false ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		if ( false === $query ) {
			$query = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) );
		}

		$query_string = wp_parse_url( $query, PHP_URL_QUERY );
		if ( ! $query_string ) {
			if ( is_array( $key ) ) {
				return array_combine( $key, array_fill( 0, count( $key ), null ) );
			}

			return null;
		}

		parse_str( $query_string, $query_args );

		if ( is_array( $key ) ) {
			$results = [];
			foreach ( $key as $k ) {
				if ( isset( $query_args[ $k ] ) ) {
						$results[ $k ] = $query_args[ $k ];
				} else {
						$results[ $k ] = null;
				}
			}
			return $results;
		}

		if ( ! isset( $query_args[ $key ] ) ) {
			return null;
		}

		return $query_args[ $key ];
	}
}

if ( ! function_exists( 'wp_parse_args_recursive' ) ) {
	/** Net als wp_parse_args maar dan voor nested arrays */
	function wp_parse_args_recursive( array $args, array $defaults = [] ): array { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		$new_args = $defaults;

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
				$new_args[ $key ] = wp_parse_args_recursive( $value, $new_args[ $key ] );
			} else {
				$new_args[ $key ] = $value;
			}
		}

		return $new_args;
	}
}

/** Wrapper om wp_hash */
function siw_hash( string $data ): string {
	return wp_hash( $data, 'siw' );
}

/** Geeft een instantie van de CSS-generator terug */
function siw_get_css_generator( array $options = [] ): Generator {
	return new Generator( $options );
}
