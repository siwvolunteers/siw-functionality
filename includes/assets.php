<?php

namespace SIW;

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
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_scripts' ] );
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

		wp_register_script( 'smoothscroll', SIW_ASSETS_URL . 'modules/smoothscroll/smoothscroll.js', [], self::SMOOTHSCROLL_VERSION, true );
		wp_enqueue_script( 'smoothscroll' );
	}
}
