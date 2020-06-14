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
	 * @param int $desktop_columns
	 * @param int $tablet_size
	 * @param int $mobile_size
	 * @return string
	 */
	public static function generate_responsive_classes( int $desktop_columns, int $tablet_columns = null, int $mobile_columns = null ) : string {
		$classes[] = 'grid-'. self::columns_to_grid_width( $desktop_columns );
		if ( null !== $tablet_columns ) {
			$classes[] = 'tablet-grid-'. self::columns_to_grid_width( $tablet_columns );
		}
		if ( null !== $mobile_columns  ) {
			$classes[] = 'mobile-grid-'. self::columns_to_grid_width( $mobile_columns );
		}
		return implode( SPACE, $classes );
	}

	/**
	 * Undocumented function
	 *
	 * @param int $column_count
	 *
	 * @return int
	 */
	public static function columns_to_grid_width( int $columns ) : int {
		switch ( $columns ) {
			case 1:
				$grid_width = 100;
				break;
			case 2:
				$grid_width = 50;
				break;
			case 3:
				$grid_width = 33;
				break;
			case 4:
				$grid_width = 25;
				break;
			case 5:
				$grid_width = 20;
				break;
			default:
				$grid_width = 100;
		}
		return $grid_width;
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
