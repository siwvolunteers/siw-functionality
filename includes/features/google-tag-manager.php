<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Asset_Attributes;
use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Config;
use SIW\External_Assets\Google_Tag_Manager as Google_Tag_Manager_Asset;
use SIW\Traits\Assets_Handle;

class Google_Tag_Manager extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		if ( is_user_logged_in() || null === Config::get_gtm_container_id() ) {
			return;
		}
		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/features/google-tag-manager.js', [ Google_Tag_Manager_Asset::get_assets_handle() ], SIW_PLUGIN_VERSION, true );

		wp_script_add_data(
			self::get_assets_handle(),
			Asset_Attributes::TYPE,
			'text/plain'
		);

		wp_script_add_data(
			self::get_assets_handle(),
			Asset_Attributes::COOKIE_CATEGORY,
			Cookie_Consent::ANALYTICAL
		);

		wp_enqueue_script( self::get_assets_handle() );
	}
}
