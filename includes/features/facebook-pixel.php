<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Config;
use SIW\Elements\Cookie_Notice;
use SIW\External_Assets\Meta_Pixel;

/**
 * Configuratie van Facebook pixel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Facebook_Pixel extends Base {

	// Script handle
	const SCRIPT_HANDLE = 'siw-facebook-pixel';

	#[Action( 'wp_enqueue_scripts' )]
	/** Voeg script toe */
	public function enqueue_script() {
		$pixel_id = Config::get_meta_pixel_id();
		if ( null !== $pixel_id ) {
			return;
		}

		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/features/facebook-pixel.js', [ 'js-cookie' ], SIW_PLUGIN_VERSION, true );
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
		wp_enqueue_script( Meta_Pixel::get_assets_handle() );
	}

	#[Filter( 'rocket_exclude_js' )]
	public function exclude_from_optimization( array $files ): array {
		$files[] = wp_make_link_relative( SIW_ASSETS_URL . 'js/features/facebook-pixel.js' );
		return $files;
	}
}
