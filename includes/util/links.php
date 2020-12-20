<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Elements;
use SIW\HTML;

/**
 * Hulpfuncties t.b.v. links
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 * 
 * @todo      escaping met wp_kses_post + regels voor svg
 */
class Links {

	/**
	 * Genereert link
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_link( string $url, string $text = null, array $attributes = [] ) : string {
		$attributes = wp_parse_args( $attributes, [ 'href' => $url ] );
		return HTML::a( $attributes, $text ?? $url );
	}

	/**
	 * Genereert externe link
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_external_link( string $url, string $text = null, array $attributes = [] ) : string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'href'             => $url,
				'target'           => '_blank',
				'rel'              => 'noopener external',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Externe link',
				'data-ga-action'   => 'Klikken',
				'data-ga-label'    => $url,
			]
		);
		return HTML::a( $attributes, $text ?? $url ); //TODO: icon 
	}

	/**
	 * Genereert tel-link
	 *
	 * @param string $phone
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_tel_link( string $phone, string $text = null, array $attributes = [] ) : string {
		$attributes = wp_parse_args( 
			$attributes,
			[ 'href' => "tel:{$phone}" ]
		);
		return HTML::a( $attributes, $text ?? $phone );
	}

	/**
	 * Genereert mailto-link
	 *
	 * @param string $email
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_mailto_link( string $email, string $text = null, array $attributes = [] ) : string {
		$email = antispambot( $email );
		$attributes['href'] = "mailto:{$email}";
		return HTML::a( $attributes, $text ?? $email );
	}

	/**
	 * Genereert link naar document
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_document_link( string $url, string $text, array $attributes = [] ) : string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'href'             => $url,
				'target'           => '_blank',
				'rel'              => 'noopener',
				'data-ga-track'    => 1,
				'data-ga-type'     => 'event',
				'data-ga-category' => 'Document',
				'data-ga-action'   => 'Downloaden',
				'data-ga-label'    => $url,
			]
		);
		return HTML::a( $attributes, $text ); //TODO: icon
	}

	/**
	 * Genereert icon-link
	 *
	 * @param string $url
	 * @param array $icon
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_icon_link( string $url, array $icon, array $attributes = [] ) : string {
		$icon = wp_parse_args(
			$icon,
			[
				'class'      => '',
				'size'       => 2,
				'background' => 'none',
			]
		);
		$attributes = wp_parse_args( 
			$attributes,
			[ 'href' => $url ]
		);
		return HTML::a(
			$attributes,
			Elements::generate_icon( $icon['class'], $icon['size'], $icon['background'] )
		);
	}

	/**
	 * Genereert link in ghost buttons
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_button_link( string $url, string $text, array $attributes = [] ) : string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'href'  => $url,
				'class' => 'button ghost',
			]
		);
		return HTML::a( $attributes, $text );
	}

	/**
	 * Genereert link in met afbeelding
	 *
	 * @param string $url
	 * @param array $image
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function generate_image_link( string $url, array $image, array $attributes = [] ) : string {
		$image = wp_parse_args(
			$image,
			[
				'src'    => '',
				'alt'    => null,
				'title'  => null,
				'width'  => null,
				'height' => null,
				'border' => 0
			]
		);

		$attributes = wp_parse_args(
			$attributes,
			[
				'href'  => $url,
			]
		);

		return HTML::a(
			$attributes,
			HTML::tag('img', $image )
		);
	}
}
