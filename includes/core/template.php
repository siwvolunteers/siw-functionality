<?php declare(strict_types=1);

namespace SIW\Core;

use Mustache_Autoloader;
use Mustache_Engine;
use Mustache_Loader_ArrayLoader;
use Mustache_Loader_CascadingLoader;
use Mustache_Loader_FilesystemLoader;
use Mustache_Template;

/**
 * SIW Widget base class
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Template {

	/**
	 * Template Engine
	 */
	private static Mustache_Engine $template_engine;

	/**
	 * Undocumented function
	 *
	 * @return Mustache_Engine
	 */
	public static function get_engine() : Mustache_Engine {
		if ( ! isset( self::$template_engine ) ) {
			Mustache_Autoloader::register();
			self::$template_engine = new Mustache_Engine(
				[
					'template_class_prefix' => '__SIW_',
					'loader'                => new Mustache_Loader_CascadingLoader( [
						new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . '/mustache' ),
						new Mustache_Loader_ArrayLoader(
							[
								'content' => '{{{ content }}}'
							]
						),
						
					]),
					'partials_loader'       => new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . '/mustache/partials'),
					'escape'                => fn( $value ) => esc_html( $value ),
					'helpers'               => [
						'json_encode'  => fn( $value ) => json_encode( $value),
						'esc_url'      => fn( $value ) => esc_url( $value ),
						'wp'           => [
							'esc_attr'     => fn( string $value ) => esc_attr( $value ),
							'wp_kses_post' => fn( string $value ) => wp_kses_post( $value ),
							'wpautop'      => fn( string $value ) => wpautop( $value ),
							'do_shortcode' => fn( string $value ) => do_shortcode( $value ),
						],
						'case' => [
							'lower' => fn( string $value ) => strtolower( $value ),
							'upper' => fn( string $value ) => strtoupper( $value ),
						],
					],
					'pragmas' => [
						Mustache_Engine::PRAGMA_FILTERS
					],
				]
			);
		}
		return self::$template_engine;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $name
	 *
	 * @return Mustache_Template
	 */
	public static function get_template( string $name ) : Mustache_Template {
		return self::get_engine()->loadTemplate( $name );
	}

	/**
	 * Undocumented function
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
	 * Undocumented function
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

