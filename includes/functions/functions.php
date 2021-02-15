<?php declare(strict_types=1);

if ( ! function_exists( 'get_query_arg' ) ) {
	/**
	 * Haalt argument uit query
	 * 
	 * @see https://core.trac.wordpress.org/ticket/34699
	 */
	function get_query_arg( $key, $query = false ) {
		if ( false === $query ) {
			$query = $_SERVER['REQUEST_URI'];
		}

		$query_string = parse_url( $query, PHP_URL_QUERY );
		if ( ! $query_string ) {
			if ( is_array( $key ) ) {
				return array_combine( $key, array_fill( 0, count( $key ), null ) );
			}

			return null;
		}

		parse_str( $query_string, $query_args );

		if ( is_array( $key ) ) {
			$results = array();
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