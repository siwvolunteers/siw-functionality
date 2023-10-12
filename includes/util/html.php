<?php declare(strict_types=1);

namespace SIW\Util;

/**
 * Hulpfuncties voor het genereren van HTML
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class HTML {

	/** Void tags */
	public static array $void_tags = [
		'area',
		'base',
		'br',
		'col',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	];

	/** Genereert HTML-tag */
	public static function tag( string $tag, array $attributes, string $content = '' ): string {

		// Void tags
		if ( in_array( $tag, self::$void_tags, true ) ) {
			return sprintf(
				'<%s %s>',
				tag_escape( $tag ),
				self::generate_attributes( $attributes )
			);
		} else {
			return sprintf(
				'<%s %s>%s</%s>',
				tag_escape( $tag ),
				self::generate_attributes( $attributes ),
				$content, // TODO: escaping met wp_kses_post?
				tag_escape( $tag )
			);
		}
	}

	/** Genereert `<a>` tag */
	public static function a( array $attributes, string $content = '' ): string {
		return self::tag( 'a', $attributes, $content );
	}

	/** Versie van sanitize_html_class voor meerdere classes*/
	public static function sanitize_html_classes( $classes, string $fallback = null ): string {
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

	/** Genereert attributes op basis van array */
	public static function generate_attributes( array $attributes ): string {
		$rendered_attributes = '';
		foreach ( $attributes as $key => $value ) {
			if ( false === $value ) {
				continue;
			}
			if ( 'class' === $key ) {
				$value = self::sanitize_html_classes( $value );
			}
			if ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}

			$rendered_attributes .= sprintf( true === $value ? ' %s' : ' %s="%s"', $key, esc_attr( $value ) );
		}
		return $rendered_attributes;
	}
}
