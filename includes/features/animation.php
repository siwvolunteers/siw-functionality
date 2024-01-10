<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\External_Assets\SAL;
use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Traits\Assets_Handle;
use SIW\Util\CSS;

/**
 * Class voor animaties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Animation extends Base {

	use Assets_Handle;

	/** Threshold voor animatie */
	private const THRESHOLD = 0.25;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Registreert scripts */
	public function register_script() {
		wp_register_script(
			self::get_assets_handle(),
			SIW_ASSETS_URL . 'js/features/animation.js',
			[ Sal::get_assets_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);
		wp_localize_script(
			self::get_assets_handle(),
			'siw_animation',
			[
				'threshold'  => self::THRESHOLD,
				'once'       => true,
				'breakpoint' => CSS::MOBILE_BREAKPOINT,
			]
		);
		wp_enqueue_script( self::get_assets_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Registreert styles */
	public function register_style() {
		$max_width = CSS::MOBILE_BREAKPOINT;
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/animation.css', [ SAL::get_assets_handle() ], SIW_PLUGIN_VERSION, "(max-width: {$max_width}px)" );
		// TODO: bug melden omdat wp_maybe_inline_styles() media query niet overneemt
		// wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/animation.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
