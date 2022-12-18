<?php declare(strict_types=1);

namespace SIW;

use SIW\Assets\JS_Cookie;
use SIW\Assets\Meta_Pixel;
use SIW\Elements\Cookie_Notice;

/**
 * Configuratie van Facebook pixel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Facebook_Pixel {

	// Script handle
	const SCRIPT_HANDLE = 'siw-facebook-pixel';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_script' ] );
	}

	/** Voeg script toe */
	public function enqueue_script() {
		$pixel_id = Config::get_meta_pixel_id();
		if ( null === $pixel_id ) {
			return;
		}

		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/siw-facebook-pixel.js', [ JS_Cookie::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::SCRIPT_HANDLE,
			'siw_facebook_pixel',
			[
				'pixel_id'    => esc_js( $pixel_id ),
				'cookie_name' => Cookie_Notice::COOKIE_NAME,
				'event_name'  => Cookie_Notice::EVENT_NAME,
			]
		);
		wp_enqueue_script( self::SCRIPT_HANDLE );
		wp_enqueue_script( Meta_Pixel::ASSETS_HANDLE );
	}
}
