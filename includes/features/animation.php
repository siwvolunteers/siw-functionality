<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Assets\SAL;
use SIW\Attributes\Action;
use SIW\Base;
use SIW\Util\CSS;

/**
 * Class voor animaties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Animation extends Base {

	/** Handle  */
	const ASSETS_HANDLE = 'siw-animation';

	/** Threshold voor animatie */
	const THRESHOLD = 0.25;

	#[Action( 'wp_enqueue_scripts' )]
	/** Registreert scripts */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/animation.js', [ Sal::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::ASSETS_HANDLE,
			'siw_animation',
			[
				'threshold'  => self::THRESHOLD,
				'once'       => true,
				'breakpoint' => CSS::MOBILE_BREAKPOINT,
			]
		);
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Registreert styles */
	public function register_style() {
		$max_width = CSS::MOBILE_BREAKPOINT;
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/animation.css', [ SAL::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, "(max-width: {$max_width}px)" );
		// TODO: bug melden omdat wp_maybe_inline_styles() media query niet overneemt
		// wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/animation.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
