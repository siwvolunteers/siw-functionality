<?php declare(strict_types=1);

namespace SIW;

use SIW\Util\CSS;

/**
 * Class voor animaties
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://mciastek.github.io/sal/
 */
class Animation {

	/** Versie van sal.js */
	CONST SAL_VERSION = '0.8.4';

	/** Threshold voor animatie */
	CONST THRESHOLD = 0.25;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_scripts' ] );
	}

	/** Registreert styles */
	public function register_styles() {
		wp_register_style( 'sal', SIW_ASSETS_URL . 'vendor/sal.js/sal.css', null, self::SAL_VERSION );
		wp_enqueue_style( 'sal' );
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
			'sal',
			CSS::generate_inline_css( $inline_css, $media_query )
		);
	}

	/** Registreert scripts */
	public function register_scripts() {
		wp_register_script( 'sal', SIW_ASSETS_URL . 'vendor/sal.js/sal.js', [], self::SAL_VERSION, true );
		wp_enqueue_script( 'sal' );
	
		wp_register_script( 'siw-animation', SIW_ASSETS_URL . 'js/siw-animation.js', ['sal'], SIW_PLUGIN_VERSION, true );
		wp_localize_script( 'siw-animation', 'siw_animation', [
			'threshold'  => self::THRESHOLD,
			'once'       => true,
			'breakpoint' => CSS::MOBILE_BREAKPOINT,
		]);
		wp_enqueue_script( 'siw-animation' );
	}
}
