<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Config;
use SIW\Data\Tag_Attribute;
use SIW\External_Assets\Google_Tag_Manager as Google_Tag_Manager_Asset;
use SIW\Traits\Class_Assets;

class Google_Tag_Manager extends Base {

	use Class_Assets;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		if ( is_user_logged_in() || null === Config::get_gtm_container_id() ) {
			return;
		}
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			[ Google_Tag_Manager_Asset::get_asset_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);

		wp_script_add_data(
			self::get_asset_handle(),
			Tag_Attribute::TYPE,
			'text/plain'
		);

		wp_script_add_data(
			self::get_asset_handle(),
			Tag_Attribute::COOKIE_CATEGORY,
			Cookie_Consent::ANALYTICAL
		);

		wp_enqueue_script( self::get_asset_handle() );
	}
}
