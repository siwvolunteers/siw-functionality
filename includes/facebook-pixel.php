<?php declare(strict_types=1);

namespace SIW;

use SIW\Elements\Cookie_Notice;

/**
 * Configuratie van Facebook pixel
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Facebook_Pixel {

	//Script handle
	const SCRIPT_HANDLE = 'siw-facebook-pixel';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_script' ] );
	}

	/** Voeg script toe */
	public function enqueue_script() {
		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/siw-facebook-pixel.js', [ 'js-cookie' ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::SCRIPT_HANDLE,
			'siw_facebook_pixel',
			[
				'pixel_id'    => siw_get_option( 'facebook.pixel_id', '' ),
				'cookie_name' => Cookie_Notice::COOKIE_NAME,
			]
		);
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}
}