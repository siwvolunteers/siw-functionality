<?php declare(strict_types=1);

namespace SIW\Core;

use Mustache_Autoloader;
use Mustache_Engine;
use Mustache_Loader_CascadingLoader;
use Mustache_Loader_FilesystemLoader;
use Mustache_Template;

/**
 * Class om Mustache templates te gebruiken
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Template {

	/** Reguliere expressie voor mustache tag */
	const MUSTACHE_TAG_REGEX = '/{{\s*.*?\s*}}/';

	/**
	 * Geeft Mustache Engine terug
	 * @todo optimalisatie
	 */
	public static function get_engine() : Mustache_Engine {

		Mustache_Autoloader::register();
		
		$template_engine = new Mustache_Engine(
			[
				'template_class_prefix' => '__SIW_',
				'loader'                => new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . 'mustache', ),
				'partials_loader'       => new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . 'mustache/partials'),
				'escape'                => fn( $value ) : string => esc_html( $value ),
				'helpers'               => [
					'json_encode'  => fn( $value ) : string => json_encode( $value),
					'esc_url'      => fn( string $value ) : string => esc_url( $value ),
					'esc_attr'     => fn( string $value ) : string => esc_attr( $value ),
					'wp_kses_post' => fn( string $value ) : string => wp_kses_post( $value ),
					'wpautop'      => fn( string $value ) : string => wpautop( $value ),
					'do_shortcode' => fn( string $value ) : string => do_shortcode( $value ),
					'antispambot'  => fn( string $value ) : string => antispambot( $value ),
					'case' => [
						'lower' => fn( string $value ) : string => strtolower( $value ),
						'upper' => fn( string $value ) : string => strtoupper( $value ),
					],
					'__'           => fn( string $value ) : string => self::translate( $value ),
				],
				'pragmas' => [
					Mustache_Engine::PRAGMA_FILTERS,
					Mustache_Engine::PRAGMA_BLOCKS,
				],
			]
		);
		return $template_engine;
	}

	/** Haalt template op */
	public static function get_template( string $name ): Mustache_Template {
		return self::get_engine()->loadTemplate( $name );
	}

	/** Rendert template */
	public static function render_template( string $name, array $context ) {
		echo self::parse_template( $name, $context );
	}

	/** Geeft geparste template terug */
	public static function parse_template( string $name, array $context ): string {
		return self::get_engine()->loadTemplate( $name )->render( $context );
	}

	/** Geeft geparste string-template terug */
	public static function parse_string_template( string $template, array $context ): string {
		$template_engine = new Mustache_Engine;
		return $template_engine->render( $template, $context );
	}

	/** Vertaal string uit template */
	public static function translate( string $value ) : string {

		//Spaties aan begin en einde weghalen
		$value = trim( $value );

		// Zoek naar Mustache tags in string
		if ( preg_match_all( self::MUSTACHE_TAG_REGEX, $value, $matches ) > 0 ) {
			
			//Vervang Mustache tags door %s
			$value = preg_replace( self::MUSTACHE_TAG_REGEX, '%s', $value );

			//Haal vertalingen op
			$value = __( $value, 'siw' ); 

			// Vervang %s weer door de Mustache tags
			return vsprintf( $value, $matches[0] );
		}
		else {
			return __( $value, 'siw' );
		}
	}
}
