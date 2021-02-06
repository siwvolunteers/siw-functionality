<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Properties;

/**
 * Hulpfuncties t.b.v. css
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class CSS {

	/** Breakpoint voor mobiel (max-width) */
	CONST MOBILE_BREAKPOINT = 768;

	/** Breakpoint voor tablet (max-width) */
	CONST TABLET_BREAKPOINT = 1024;

	/** CSS klasse om content op mobiel te verbergen */
	const HIDE_ON_MOBILE_CLASS = 'hide-on-mobile';

	/** CSS klasse om content op tablet te verbergen */
	const HIDE_ON_TABLET_CLASS = 'hide-on-tablet';

	/** CSS klasse om content op desktop te verbergen */
	const HIDE_ON_DESKTOP_CLASS = 'hide-on-desktop';

	/** Genereert reponsive classes */
	public static function generate_responsive_classes( int $desktop_columns, int $tablet_columns = null, int $mobile_columns = null ) : string {
		$classes[] = 'grid-'. self::columns_to_grid_width( $desktop_columns );
		if ( is_int( $tablet_columns ) ) {
			$classes[] = 'tablet-grid-'. self::columns_to_grid_width( $tablet_columns );
		}
		if ( is_int( $mobile_columns ) ) {
			$classes[] = 'mobile-grid-'. self::columns_to_grid_width( $mobile_columns );
		}
		return implode( SPACE, $classes );
	}

	/** Converteert aantal kolommen naar grid breedte */
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

	/** Genereert css o.b.v. array met regels */
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

	/** Voegt css variabelen toe aan script */
	public static function add_css_variables( string $handle ) {
		$css_variables = [
			'--siw-primary-color'    => Properties::PRIMARY_COLOR,
			'--siw-secondary-color'  => Properties::SECONDARY_COLOR,
			'--siw-font-color'       => Properties::FONT_COLOR,
			'--siw-font-color-light' => Properties::FONT_COLOR_LIGHT,
		];
		//CSS-variabelen toevoegen als toegestane css properties
		add_filter(
			'safe_style_css',
			fn( array $allowed_attr ) : array => array_merge( $allowed_attr, array_keys( $css_variables ) )
		);

		wp_add_inline_style(
			$handle,
			CSS::generate_inline_css( [':root' => $css_variables])
		);
	}
}
