<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Elements;
use SIW\HTML;

/**
 * Hulpfuncties t.b.v. links
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      escaping met wp_kses_post + regels voor svg
 */
class Links {

	/** Genereert link */
	public static function generate_link( string $url, string $text = null, array $attributes = [] ) : string {
		$attributes = wp_parse_args( $attributes, [ 'href' => $url ] );
		return HTML::a( $attributes, $text ?? $url );
	}

	/** Genereert externe link */
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

	/** Genereert mailto-link */
	public static function generate_mailto_link( string $email, string $text = null, array $attributes = [] ) : string {
		$email = antispambot( $email );
		$attributes['href'] = "mailto:{$email}";
		return HTML::a( $attributes, $text ?? $email );
	}

	/** Genereert link naar document */
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

	/** Genereert link in ghost buttons */
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

	/** Genereert link in met afbeelding */
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
