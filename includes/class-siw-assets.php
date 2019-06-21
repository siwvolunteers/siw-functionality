<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class om scripts en styles te registreren
 * 
 * @package     SIW
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Assets {

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
		wp_register_style( 'siw', SIW_ASSETS_URL . 'css/siw.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw' );
	}

	/**
	 * Registreert scripts
	 */
	public function register_scripts() {
	}

}
