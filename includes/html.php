<?php declare(strict_types=1);

namespace SIW;

/**
 * Hulpfuncties voor het genereren van HTML
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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
	public static function tag( string $tag, array $attributes, string $content = '' ) : string {

		//Void tags
		if ( in_array( $tag, self::$void_tags ) ) {
			return sprintf(
				'<%s %s>',
				tag_escape( $tag ),
				self::generate_attributes( $attributes )
			);
		}
		else {
			return sprintf(
				'<%s %s>%s</%s>',
				tag_escape( $tag ),
				self::generate_attributes( $attributes ),
				$content, //TODO: escaping met wp_kses_post?
				tag_escape( $tag )
			);
		}
	}

	/** Genereert `<a>` tag */
	public static function a( array $attributes, string $content = '') : string {
		return self::tag( 'a', $attributes, $content );
	}

	/** sanitize_html_class voor meerdere classes*/
	public static function sanitize_html_classes( $class, string $fallback = null ) : string {
		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}
		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map( 'sanitize_html_class', $class );
			return implode( ' ', $class );
		}
		else {
			return sanitize_html_class( $class, $fallback );
		}
	}

	/** Genereert attributes op basis van array */
	public static function generate_attributes( array $attributes ) : string {
		$rendered_attributes = '';
		foreach ( $attributes as $key => $value ) {
			if ( false == $value ) {
				continue;
			}
			if ( 'class' == $key ) {
				$value = self::sanitize_html_classes( $value );
			}
			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}

			$rendered_attributes .= sprintf( true === $value ? ' %s' : ' %s="%s"', $key, esc_attr( $value ) );
		}
		return $rendered_attributes;
	}
}
