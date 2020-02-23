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
	 * Rendert html-tag
	 *
	 * @param string $type
	 * @param array $attributes
	 * @return string
	 */
	public static function render_tag( string $type, array $attributes, $content = null, $close = false ) {
		echo self::generate_tag( $type, $attributes, $content, $close );
	}

	/**
	 * Genereert html-tag
	 *
	 * @param string $type
	 * @param array $attributes
	 * @return string
	 */
	public static function generate_tag( string $type, array $attributes, $content = null, $close = false ) {
		$tag = sprintf(
			'<%s %s>',
			tag_escape( $type ),
			self::generate_attributes( $attributes )
		);
		if ( null !== $content ) {
			$tag .= $content;
		}

		if ( true === $close ) {
			$tag .= sprintf( '</%s>', tag_escape( $type ) );
		}

		return $tag;
	}

	/**
	 * Genereert tabel
	 *
	 * @param array $rows
	 * @param array $headers
	 * @param array $attributes
	 * 
	 * @return string
	 */
	public static function generate_table( array $rows, array $headers = [], $attributes = [] ) {

		$table = sprintf( '<table %s>', self::generate_attributes( $attributes ) );
		if ( ! empty( $headers ) ) {
			$table .= '<tr>';
			foreach ( $headers as $header ) {
				$table .= '<th>' . wp_kses_post( $header ) . '</th>';
			}
			$table .= '</tr>';
		}

		foreach ( $rows as $row ) {
			$table .= '<tr>';
			foreach ( $row as $cell ) {
				$table .= '<td>' . wp_kses_post( $cell ) . '</td>';
			}
			$table .= '</tr>';
		}
		$table .= '</table>';

		return $table;
	}

	/**
	 * Genereert een `<ol>` of `<ul>` lijst van array
	 * @param array $items
	 * @param bool $ordered
	 *
	 * @return string
	 *
	 * @todo escaping + generate_tag gebruiken + attributes
	 */
	public static function generate_list( $items, bool $ordered = false ) {
		if ( ! is_array( $items ) || empty ( $items ) ) {
			return false;
		}
		$tag = $ordered ? 'ol' : 'ul';

		$list = "<{$tag}>";
		foreach ( $items as $item ) {
			$list .= '<li>' . wp_kses_post( $item ) . '</li>';
		}
		$list .= "</{$tag}>";

		return $list;
	}

	/**
	 * Genereert link
	 *
	 * @todo attributes, target en rel
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 * @param array $icon
	 * @return string
	 */
	public static function generate_link( $url, $text = null, array $attributes = [], array $icon = [] ) {

		if ( null === $text ) {
			$text = $url;
		}

		if ( ! empty( $icon ) ) {
			$icon = wp_parse_args(
				$icon,
				[
					'class'      => '',
					'size'       => 2,
					'background' => 'none',
				]
			);
			$icon_html = Formatting::generate_icon( $icon['class'], $icon['size'], $icon['background'] );
		}
		else { 
			$icon_html = '';
		}

		$link = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $url ),
			self::generate_attributes( $attributes ),
			wp_kses_post( $text ) . $icon_html
		);
		return $link;
	}

	/**
	 * Genereert externe link
	 *
	 * @param  string $url
	 * @param  string $text
	 * @return string
	 */
	public static function generate_external_link( string $url, string $text = null ) {
		return self::generate_link(
			$url,
			$text . '&nbsp;',
			[
				'target'           => '_blank',
				'rel'              => 'noopener external',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Externe link',
				'data-ga-action'   => 'Klikken',
				'data-ga-label'    => $url,
			],
			[
				'class'      => 'siw-icon-external-link-alt',
			]
		);
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
	public static function generate_attributes( array $attributes ) {
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
	
				$field .= sprintf( '<select %s>', self::generate_attributes( $attributes ) );
				if ( ! empty( $options ) && is_array( $options ) ) {
					foreach ( $options as $key => $option ) {
						$selected = selected( $attributes['value'], $key, false );
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
