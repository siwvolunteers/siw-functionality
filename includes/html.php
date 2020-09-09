<?php

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
	 *
	 * @var array
	 */
	public static $void_tags = [
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

	/**
	 * Genereert form field
	 *
	 * @param string $type
	 * @param array $input_args
	 * @param array $wrapper_args
	 * @return string
	 * 
	 * @todo eigen class: Form
	 */
	public static function generate_field( string $type, array $input_args, array $wrapper_args = [] ) {

		$input_args = wp_parse_args( $input_args, [
			'id'          => '',
			'name'        => '',
			'value'       => '',
			'options'     => [],
			'placeholder' => '',
			'pattern'     => '',
			'class'       => '',
			'disabled'    => false,
			'required'    => false,
			'attributes'  => [],
			'options'     => [],
		]);

		$attributes = wp_parse_args( $input_args['attributes'], [
			'disabled'    => $input_args['disabled'],
			'required'    => $input_args['required'],
			'class'       => $input_args['class'],
			'id'          => $input_args['id'],
			'name'        => $input_args['name'],
			'value'       => $input_args['value'],
			'placeholder' => $input_args['placeholder'],
		]);

		$field = '';

		if ( ! empty( $input_args['label'] ) ) {
			$field .= sprintf( '<label for="%s">%s</label>', esc_attr( $input_args['id'] ), esc_html( $input_args['label'] ) );
		}

		switch ( $type ) {
			case 'text':
			case 'tel':
			case 'email':
			case 'url':
			case 'number':
			case 'hidden':
			case 'date':
			case 'submit':
				$field .= sprintf('<input type="%s" %s>', esc_attr( $type ), self::generate_attributes( $attributes ) );
				break;
			case 'radio':
				$options = $input_args['options'];
				$value = $attributes['value'];
				if ( ! empty( $options ) && is_array( $options ) ) {
					foreach ( $options as $key => $option ) {
						$checked = checked( $value, $key, false );
						$field .= sprintf( '<label><input type="radio" value="%s" %s %s>%s</label>', esc_attr( $key ), self::generate_attributes( $attributes ), $checked, esc_html( $option ) );
					}
				}
				break;

			case 'select':
				$options = $input_args['options'];
				$value = $attributes['value'];
				unset( $attributes['value'] );
	
				$field .= sprintf( '<select %s>', self::generate_attributes( $attributes ) );
				if ( ! empty( $options ) && is_array( $options ) ) {
					foreach ( $options as $key => $option ) {
						$selected = selected( $value, $key, false );
						$field .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), $selected, esc_html( $option ) );
					}
				}
				$field .= '</select>';
				break;

			case 'checkbox':
				$value = $attributes['value'];
				unset( $attributes['value'] );
				$field .= sprintf( '<label for="%s">', esc_attr( $attributes['id'] ) );
				$field .= sprintf( '<input type="checkbox" %s %s/>', self::generate_attributes( $attributes ), checked( 1, $value, false ) );
				$field .= sprintf( '%s</label>', esc_html( $input_args['label'] ) );
				break;

			case 'textarea':
				$value = $attributes['value'];
				unset( $attributes['value'] );
				$field .= sprintf( '<textarea %s>%s</textarea>', self::generate_attributes( $attributes ), $value );
				break;

			default:
				return false;
		}

		/* Wrapper toevoegen */
		$wrapper_args = wp_parse_args( $wrapper_args, [
			'tag'   => '',
			'class' => [],
			]
		);

		if ( ! empty( $wrapper_args['tag'] ) ) {
			$wrapper_class = '';
			if ( ! empty( $wrapper_args['class'] ) ) {
				$wrapper_class = sprintf( 'class="%s"', self::sanitize_html_classes( $wrapper_args['class'] ) );
			}

			$wrapper_open = sprintf( '<%s %s>', tag_escape( $wrapper_args['tag'] ), $wrapper_class );
			$wrapper_close = sprintf( '</%s>', tag_escape( $wrapper_args['tag'] ) );

			$field = $wrapper_open . $field . $wrapper_close;
		}

		return $field;
	}

}
