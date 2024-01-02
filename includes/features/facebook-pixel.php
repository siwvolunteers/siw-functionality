<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Asset_Attributes;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Config;
use SIW\External_Assets\Meta_Pixel;
use SIW\Traits\Assets_Handle;

/**
 * Configuratie van Facebook pixel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Facebook_Pixel extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voeg script toe */
	public function enqueue_script() {
		$pixel_id = Config::get_meta_pixel_id();
		if ( null === $pixel_id ) {
			return;
		}

		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/features/facebook-pixel.js', [], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::get_assets_handle(),
			'siw_facebook_pixel',
			[
				'pixel_id' => esc_js( $pixel_id ),
			]
		);

		wp_script_add_data(
			self::get_assets_handle(),
			Asset_Attributes::TYPE,
			'text/plain'
		);

		wp_script_add_data(
			self::get_assets_handle(),
			Asset_Attributes::COOKIE_CATEGORY,
			Cookie_Consent::MARKETING
		);
		wp_enqueue_script( self::get_assets_handle() );
		wp_enqueue_script( Meta_Pixel::get_assets_handle() );
	}
}
