<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Util\HTML;

class Links {

	public static function generate_link( string $url, string $text = null, array $attributes = [] ): string {
		$attributes = wp_parse_args( $attributes, [ 'href' => $url ] );
		return HTML::a( $attributes, $text ?? $url );
	}

	public static function generate_external_link( string $url, string $text = null, array $attributes = [] ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'href'   => $url,
				'target' => '_blank',
				'rel'    => 'noopener external',
			]
		);
		return HTML::a( $attributes, $text ?? $url );
	}

	public static function generate_mailto_link( string $email, string $text = null, array $attributes = [] ): string {
		$email = antispambot( $email );
		$attributes['href'] = "mailto:{$email}";
		return HTML::a( $attributes, $text ?? $email );
	}

	public static function generate_button_link( string $url, string $text, array $attributes = [] ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'href'  => $url,
				'class' => 'button',
			]
		);
		return HTML::a( $attributes, $text );
	}

	public static function generate_image_link( string $url, array $image, array $attributes = [] ): string {
		$image = wp_parse_args(
			$image,
			[
				'src'    => '',
				'alt'    => null,
				'title'  => null,
				'width'  => null,
				'height' => null,
				'border' => 0,
			]
		);

		$attributes = wp_parse_args(
			$attributes,
			[
				'href' => $url,
			]
		);

		return HTML::a(
			$attributes,
			HTML::tag( 'img', $image )
		);
	}
}
