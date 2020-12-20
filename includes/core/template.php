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
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Template {

	/**
	 * Template Engine
	 */
	private static Mustache_Engine $template_engine;

	/**
	 * Geeft gedeelde instantie van Mustache Engine terug
	 *
	 * @return Mustache_Engine
	 */
	public static function get_engine() : Mustache_Engine {

		if ( isset( self::$template_engine ) ) {
			return self::$template_engine;
		}

		Mustache_Autoloader::register();
		
		$template_dirs = [
			SIW_TEMPLATES_DIR . '/mustache',
		];

		//Filter directories (t.b.v. extensies)
		$template_dirs = apply_filters( 'siw_mustache_template_dirs', $template_dirs );

		$loaders = array_map(
			fn( string $dir ) : Mustache_Loader_FilesystemLoader => new Mustache_Loader_FilesystemLoader( $dir ),
			$template_dirs
		);
		
		self::$template_engine = new Mustache_Engine(
			[
				'template_class_prefix' => '__SIW_',
				'loader'                => new Mustache_Loader_CascadingLoader( $loaders ),
				'partials_loader'       => new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . '/mustache/partials'),
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
				],
				'pragmas' => [
					Mustache_Engine::PRAGMA_FILTERS,
					Mustache_Engine::PRAGMA_BLOCKS,
				],
			]
		);
		return self::$template_engine;
	}

	/**
	 * Haalt template op
	 *
	 * @param string $name
	 *
	 * @return Mustache_Template
	 */
	public static function get_template( string $name ) : Mustache_Template {
		return self::get_engine()->loadTemplate( $name );
	}

	/**
	 * Rendert template
	 *
	 * @param string $name
	 * @param array $context
	 *
	 * @return string
	 */
	public static function render_template( string $name, array $context ) {
		echo self::parse_template( $name, $context );
	}

	/**
	 * Geeft geparste template terug
	 *
	 * @param string $name
	 * @param array $context
	 *
	 * @return string
	 */
	public static function parse_template( string $name, array $context ) : string {
		return self::get_engine()->loadTemplate( $name )->render( $context );
	}
}

