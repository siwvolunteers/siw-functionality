<?php declare(strict_types=1);

namespace SIW;

/**
 * Hulpfuncties voor het genereren van HTML
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class HTML {

	/**
	 * Void tags
	 */
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

	/**
	 * Genereert HTML-tag
	 *
	 * @param string $tag
	 * @param string $content
	 * @param array $attributes
	 *
	 * @return string
	 */
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

	/**
	 * Genereert `<div>` tag
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function div( array $attributes, string $content = '' ) : string {
		return self::tag( 'div', $attributes, $content );
	}

	/**
	 * Genereert `<span>` tag
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function span(  array $attributes, string $content = '' ) : string {
		return self::tag( 'span', $attributes, $content );
	}

	/**
	 * Genereert `<a>` tag
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function a( array $attributes, string $content = '') : string {
		return self::tag( 'a', $attributes, $content );
	}

	/**
	 * Genereert `<svg>` tag
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function svg( array $attributes, string $content = '' ) : string {
		return self::tag( 'svg', $attributes, $content );
	}

	/**
	 * Genereert `<li>` tag
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	public static function li( array $attributes, string $content = '' ) : string {
		return self::tag( 'li', $attributes, $content );
	}

	/**
	* sanitize_html_class voor meerdere classes
	*
	* @param  mixed $class
	* @param  string $fallback
	* @return string
	*/
	public static function sanitize_html_classes( $class, $fallback = null ) {
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

	/**
	 * Genereert attributes op basis van array
	 *
	 * @param array $attributes
	 * @return string
	 */
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
