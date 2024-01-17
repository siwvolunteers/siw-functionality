<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Traits\Assets_Handle;

class Plugin_Styles extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/plugin-styles.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/features/plugin-styles.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
