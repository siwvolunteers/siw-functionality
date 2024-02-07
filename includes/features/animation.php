<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\External_Assets\SAL;
use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Breakpoint;
use SIW\Traits\Class_Assets;

class Animation extends Base {

	use Class_Assets;

	private const THRESHOLD = 0.25;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function register_script() {
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			[ Sal::get_asset_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);
		wp_localize_script(
			self::get_asset_handle(),
			'siw_animation',
			[
				'threshold'  => self::THRESHOLD,
				'once'       => true,
				'breakpoint' => Breakpoint::MOBILE->value,
			]
		);
		wp_enqueue_script( self::get_asset_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function register_style() {
		$max_width = Breakpoint::MOBILE->value;
		wp_register_style( self::get_asset_handle(), self::get_style_asset_url(), [ SAL::get_asset_handle() ], SIW_PLUGIN_VERSION, "(max-width: {$max_width}px)" );
		// TODO: bug melden omdat wp_maybe_inline_styles() media query niet overneemt
		// wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/animation.css' );
		wp_enqueue_style( self::get_asset_handle() );
	}
}
