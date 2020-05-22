<?php

namespace SIW\Util;

/**
 * Hulpfuncties t.b.v. css
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class CSS {

	/**
	 * Genereert reponsive classes
	 *
	 * @param int $column_size
	 * @param int $tablet_size
	 * @param int $mobile_size
	 * @return string
	 */
	public static function generate_responsive_class( int $column_size, int $tablet_size = null, int $mobile_size = null ) {
		
		$class = "grid-{$column_size}";

		if ( null !== $tablet_size ) {
			$class .= SPACE . "tablet-grid-{$tablet_size}";
		}

		if ( null !== $mobile_size  ) {
			$class .= SPACE . "mobile-grid-{$mobile_size}";
		}
		return $class;
	}

	/**
	 * Genereert css o.b.v. array met regels
	 *
	 * @param array $rules
	 * @param array $media_query
	 *
	 * @return string
	 */
	public static function generate_inline_css( array $rules, array $media_query = [] ) {
		$css = '';
		foreach ( $rules as $selector => $styles ) {
			$css .= $selector . '{' . PHP_EOL;
			foreach ( $styles as $property => $value ) {
				$css .= safecss_filter_attr( sprintf( '%s:%s', $property, $value ) ) . ';' . PHP_EOL;
			}
			$css .= '}' . PHP_EOL;
		}

		// Media query toevoegen indien van toepassing
		if ( ! empty( $media_query ) ) {
			$rendered_media_query = '@media only screen';
			foreach ( $media_query as $property => $value ) {
				$rendered_media_query .= sprintf( ' and (%s)', safecss_filter_attr( sprintf( '%s:%s;', $property, $value ) ) );
			}

			$css = $rendered_media_query . '{' . $css . '}';
		}
		return $css;
	}
}
