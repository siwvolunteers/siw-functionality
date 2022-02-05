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
	CONST THRESHOLD = 0.25;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_script' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_style' ] );
	}

	/** Registreert scripts */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/siw-animation.js', [ Sal::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script( self::ASSETS_HANDLE, 'siw_animation', [
			'threshold'  => self::THRESHOLD,
			'once'       => true,
			'breakpoint' => CSS::MOBILE_BREAKPOINT,
		]);
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** Registreert styles */
	public function register_style() {
		wp_enqueue_style( SAL::ASSETS_HANDLE );
		
		$inline_css = [
			"[data-sal|='fade']" => [
				'opacity' => 1,
			],
			"[data-sal|='slide']"=> [
				'opacity'   => 1,
				'transform' => 'none',
			],
			"[data-sal|='zoom']" => [
				'opacity'   => 1,
				'transform' => 'none',
			],
			"[data-sal|='flip']" => [
				'transform' => 'none',
			],
		];
		$media_query = [
			'max-width' => CSS::MOBILE_BREAKPOINT . 'px',
		];
		wp_add_inline_style(
			SAL::ASSETS_HANDLE,
			CSS::generate_inline_css( $inline_css, $media_query )
		);
	}
}
