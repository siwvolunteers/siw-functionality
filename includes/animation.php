<?php declare(strict_types=1);

namespace SIW;

use SIW\Assets\SAL;
use SIW\Util\CSS;

/**
 * Class voor animaties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Animation {

	/** Handle  */
	const ASSETS_HANDLE = 'siw-animation';

	/** Threshold voor animatie */
	const THRESHOLD = 0.25;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_script' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_style' ] );
	}

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

	/** Registreert styles */
	public function register_style() {
		$max_width = CSS::MOBILE_BREAKPOINT;
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/animation.css', [ SAL::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, "(max-width: {$max_width}px)" );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
