<?php declare(strict_types=1);

use SIW\Interfaces\Enums\Labels;

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

if ( ! function_exists( 'build_html_attributes' ) ) {
	function build_html_attributes( array $attributes ): string {  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		$rendered_attributes = '';
		foreach ( $attributes as $key => $value ) {
			if ( false === $value ) {
				continue;
			}
			if ( 'class' === $key ) {
				$value = sanitize_html_classes( $value );
			}
			if ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}

			$rendered_attributes .= sprintf( true === $value ? ' %s' : ' %s="%s"', $key, esc_attr( $value ) );
		}
		return $rendered_attributes;
	}
}

if ( ! function_exists( 'sanitize_html_classes' ) ) {
	function sanitize_html_classes( string|array $classes, string $fallback = null ): string {  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		if ( is_string( $classes ) ) {
			$classes = explode( ' ', $classes );
		}
		if ( is_array( $classes ) && count( $classes ) > 0 ) {
			$classes = array_map( 'sanitize_html_class', $classes );
			return implode( ' ', $classes );
		} else {
			return sanitize_html_class( $classes, $fallback );
		}
	}
}


/** Wrapper om wp_hash */
function siw_hash( string $data ): string {
	return wp_hash( $data, 'siw' );
}
