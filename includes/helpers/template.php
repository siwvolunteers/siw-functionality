<?php declare(strict_types=1);

namespace SIW\Helpers;

use Mustache_Engine;
use Mustache_Loader_CascadingLoader;
use Mustache_Loader_FilesystemLoader;
use Mustache_Loader_StringLoader;

/**
 * Class om een Mustache template te gebruiken
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Template {

	/** Reguliere expressie voor mustache tag */
	private const MUSTACHE_TAG_REGEX = '/{{\s*.*?\s*}}/';

	/** Mustache Engine */
	protected static ?Mustache_Engine $engine = null;

	/** Template */
	protected string $template;

	/** Context */
	protected array $context = [];

	/** Constructor */
	protected function __construct() {}

	/** Creëer email */
	public static function create(): self {
		$self = new self();

		if ( null === self::$engine ) {
			self::$engine = self::create_engine();
		}

		return $self;
	}

	/** Zet context */
	public function set_context( array $context ): self {
		$this->context = $context;
		return $this;
	}

	/** Zet template (string of naam) */
	public function set_template( string $template ): self {
		$this->template = $template;
		return $this;
	}

	/** Rendert template */
	public function render_template() {
		echo $this->parse_template(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/** Geeft geparste template terug */
	public function parse_template(): string {
		return self::$engine->loadTemplate( $this->template )->render( $this->context );
	}

	/** Creëert Mustache engine */
	protected static function create_engine() {
		return new Mustache_Engine(
			[
				'template_class_prefix' => '__SIW_',
				'loader'                => new Mustache_Loader_CascadingLoader(
					[
						new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . 'mustache', ),
						new Mustache_Loader_StringLoader(),
					]
				),
				'partials_loader'       => new Mustache_Loader_FilesystemLoader( SIW_TEMPLATES_DIR . 'mustache/partials' ),
				'escape'                => fn( $value ): string => esc_html( $value ),
				'helpers'               => [
					'json_encode'   => fn( $value ): string => wp_json_encode( $value ),
					'esc_url'       => fn( string $value ): string => esc_url( $value ),
					'esc_attr'      => fn( string $value ): string => esc_attr( $value ),
					'wp_kses_post'  => fn( string $value ): string => wp_kses_post( $value ),
					'wpautop'       => fn( string $value ): string => wpautop( $value ),
					'do_shortcode'  => fn( string $value ): string => do_shortcode( $value ),
					'antispambot'   => fn( string $value ): string => antispambot( $value ),
					'urlencode'     => fn( string $value ): string => rawurlencode( $value ),
					'base64_encode' => fn( string $value ): string => base64_encode( $value ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					'siw_hash'      => fn( string $value ): string => siw_hash( $value ),
					'case'          => [
						'lower' => fn( string $value ): string => strtolower( $value ),
						'upper' => fn( string $value ): string => strtoupper( $value ),
					],
					'__'            => fn( string $value ): string => self::translate( $value ),
				],
				'pragmas'               => [
					Mustache_Engine::PRAGMA_FILTERS,
					Mustache_Engine::PRAGMA_BLOCKS,
				],
			]
		);
	}

	/** Vertaal string uit template TODO: verplaatsen naar Mustache Util? */
	public static function translate( string $value ): string {

		// Spaties aan begin en einde weghalen
		$value = trim( $value );

		// Zoek naar Mustache tags in string
		if ( preg_match_all( self::MUSTACHE_TAG_REGEX, $value, $matches ) > 0 ) {

			// Vervang Mustache tags door %s
			$value = preg_replace( self::MUSTACHE_TAG_REGEX, '%s', $value );

			// Haal vertalingen op
			$value = __( $value, 'siw' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText

			// Vervang %s weer door de Mustache tags
			return vsprintf( $value, $matches[0] );
		} else {
			return __( $value, 'siw' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		}
	}
}
