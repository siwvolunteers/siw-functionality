<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Config;
use SIW\Data\Tag_Attribute;
use SIW\External_Assets\Meta_Pixel;
use SIW\Traits\Class_Assets;

class Facebook_Pixel extends Base {

	use Class_Assets;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_script() {
		$pixel_id = Config::get_meta_pixel_id();
		if ( null === $pixel_id ) {
			return;
		}

		wp_register_script( self::get_asset_handle(), self::get_script_asset_url(), [], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::get_asset_handle(),
			'siw_facebook_pixel',
			[
				'pixel_id' => esc_js( $pixel_id ),
			]
		);

		wp_script_add_data(
			self::get_asset_handle(),
			Tag_Attribute::TYPE->value,
			'text/plain'
		);

		wp_script_add_data(
			self::get_asset_handle(),
			Tag_Attribute::COOKIE_CATEGORY->value,
			Cookie_Consent::MARKETING
		);
		wp_enqueue_script( self::get_asset_handle() );
		wp_enqueue_script( Meta_Pixel::get_asset_handle() );
	}
}
