<?php

/**
 * Hulpfuncties voor het genereren van HTML
 *
 * @package   SIW
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_HTML {

	/**
	 * Genereert html-tag
	 *
	 * @param string $tag
	 * @param array $attributes
	 * @return string
	 */
	public static function generate_tag( string $tag, array $attributes, $content, $close = false ) {
		$tag = sprintf(
			'<%s %s>',
			tag_escape( $tag ),
			self::render_attributes( $attributes )
		);
		if ( true === $close ) {
			$tag .= sprintf( '</%s>', tag_escape(( $tag ) ) );
		}

		return $tag;
	}


	/**
	 * Genereert een `<ol>` of `<ul>` lijst van array
	 * @param array $items
	 * @param bool $ordered
	 *
	 * @return string
	 *
	 * @todo escaping
	 */
	public static function generate_list( array $items, bool $ordered = false ) {
		if ( empty ( $items ) ) {
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
	 * @param string $icon_class
	 * @return string
	 */
	public static function generate_link( $url, $text = null, array $attributes = [], string $icon_class = null ) {

		if ( null === $text ) {
			$text = $url;
		}
		$icon_html = ( $icon_class) ? SIW_Formatting::generate_icon( $icon_class, 1 ) : '';

		$link = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $url ),
			self::render_attributes( $attributes ),
			wp_kses_post( $text . $icon_html )
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
				'rel'              => 'noopener',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Externe link',
				'data-ga-action'   => 'Klikken',
				'data-ga-label'    => $url,
			],
			'siw-icon-external-link-alt'
		);
	}

	/**
	* sanitize_html_class voor meerdere classes
	*
	* @param  mixed $class
	* @param  string $fallback
	* @return string
	*/
	protected static function sanitize_html_classes( $class, $fallback = null ) {
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

}