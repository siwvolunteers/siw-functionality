<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\External_Assets\Carbon_Badge as Carbon_Badge_Asset;
use SIW\Traits\Assets_Handle;

class Carbon_Badge extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		wp_enqueue_script( Carbon_Badge_Asset::get_assets_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/carbon-badge.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/features/carbon-badge.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

	#[Add_Action( 'generate_before_copyright', 99 )]
	public function add_carbon_badge(): void {
		echo '<div class="siw-carbon-badge"><div id="wcb" class="carbonbadge wcb-d"></div></div>';
	}
}
