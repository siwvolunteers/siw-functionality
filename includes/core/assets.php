<?php

namespace SIW\Core;

/**
 * Class om scripts en styles te registreren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Assets {

	/**
	 * Versie van JS Cookie
	 */
	const JSCOOKIE_VERSION = '2.2.1';

	/**
	 * Versie van SmoothScroll
	 */
	const SMOOTHSCROLL_VERSION = '1.4.10';

	/**
	 * Versie van Balloon.css
	 */
	const BALLOON_VERSION = '1.0.4';

	/**
	 * Versie van Polyfill.io
	 */
	const POLYFILL_VERSION = '3.52.1';

	/**
	 * Features voor Polyfill.io
	 *
	 * @var array
	 */
	protected $polyfill_features = [
		'default'
	];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_scripts' ] );
		add_filter( 'script_loader_tag', [ $self, 'set_crossorigin' ], 10, 2 );
		add_filter( 'rocket_minify_excluded_external_js', [ $self, 'add_polyfill_url' ] );
	}

	/**
	 * Registreert styles
	 */
	public function register_styles() {
		wp_register_style( 'siw', SIW_ASSETS_URL . 'css/siw.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw' );

		wp_register_style( 'balloon', SIW_ASSETS_URL . 'modules/balloon/balloon.css', [], self::BALLOON_VERSION );
		wp_enqueue_style( 'balloon' );
	}

	/**
	 * Registreert scripts
	 * 
	 * @todo option voor smoothscroll
	 */
	public function register_scripts() {
		//JS-cookie niet zelf enqueuen; is dependency van andere scripts
		wp_register_script( 'js-cookie', SIW_ASSETS_URL . 'modules/js-cookie/js.cookie.js', [], self::JSCOOKIE_VERSION, true );

		//SIW-svg script niet zelf enqueuen, wordt gebruikt door andere classes
		wp_register_script( 'siw-svg', SIW_ASSETS_URL . 'js/siw-svg.js', [], SIW_PLUGIN_VERSION, true );

		//Polyfill niet zelf enqueuen; is dependency van andere scripts
		$polyfill_url = add_query_arg(
			[
				'version'  => self::POLYFILL_VERSION,
				'features' => implode( ',', $this->polyfill_features ), //TODO: filter?
				'flags'    => 'gated'
			],
			'https://polyfill.io/v3/polyfill.min.js'
			);
		wp_register_script( 'polyfill', $polyfill_url, [], null, true );
		wp_script_add_data( 'polyfill', 'crossorigin', 'anonymous' );

		//Smoothscroll wel enqueuen 
		wp_register_script( 'smoothscroll', SIW_ASSETS_URL . 'modules/smoothscroll/smoothscroll.js', [], self::SMOOTHSCROLL_VERSION, true );
		wp_enqueue_script( 'smoothscroll' );
	}

	/**
	 * Zet crossorigin attribute
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function set_crossorigin( string $tag, string $handle ) {
		$crossorigin = wp_scripts()->get_data( $handle, 'crossorigin' );
		if ( $crossorigin ) {
			$tag = str_replace(
				'></',
				sprintf( ' crossorigin="%s"></', esc_attr( $crossorigin ) ),
				$tag
			);
		}
		return $tag;
	}

	/**
	 * Sluit Polyfill uit van optimalisatie
	 *
	 * @param array $urls
	 *
	 * @return array
	 */
	public function add_polyfill_url( array $urls ) {
		$urls[] = 'https://polyfill.io';
		return $urls;
	}
}
