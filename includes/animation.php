<?php

namespace SIW;

use SIW\CSS;
use SIW\Util;

/**
 * Class voor animaties
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://mciastek.github.io/sal/
 */
class Animation {

	/**
	 * Versie van sal.js
	 */
	CONST SAL_VERSION = '0.7.4';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'register_scripts' ] );
	}

	/**
	 * Registreert styles
	 */
	public function register_styles() {
		wp_register_style( 'sal', SIW_ASSETS_URL . 'modules/sal/sal.css', null, self::SAL_VERSION );
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
			'max-width' => Util::get_mobile_breakpoint() . 'px',
		];
		wp_add_inline_style(
			'sal',
			CSS::generate_inline_css( $inline_css, $media_query )
		);
	}

	/**
	 * Registreert scripts
	 */
	public function register_scripts() {
		wp_register_script( 'sal', SIW_ASSETS_URL . 'modules/sal/sal.js', [], self::SAL_VERSION, true );
		wp_enqueue_script( 'sal' );
	
		wp_register_script( 'siw-animation', SIW_ASSETS_URL . 'js/siw-animation.js', ['sal'], SIW_PLUGIN_VERSION, true );
		wp_localize_script( 'siw-animation', 'siw_animation', [
			'threshold'  => 0.5,
			'once'       => true,
			'breakpoint' => Util::get_mobile_breakpoint(),
		]);
		wp_enqueue_script( 'siw-animation' );
	}

	/**
	 * Geeft opties voor duur terug
	 *
	 * @return array
	 */
	public static function get_duration_options() {
		for ( $t = 200; $t <= 2000; $t+=50 ) {
			$durations[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}
		return $durations;
	}

	/**
	 * Geeft opties voor vertraging terug
	 *
	 * @return array
	 */
	public static function get_delay_options() {
		$delays['none'] = __( 'Geen', 'siw' );
		for ( $t = 100; $t <= 1000; $t+=50 ) {
			$delays[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}
		return $delays;
	}

	/**
	 * Geeft opties voor easing terug
	 *
	 * @return array
	 */
	public static function get_easing_options() {
		$easings = [
			'linear'            => 'linear',
			'ease'              => 'ease',
			'ease-in'           => 'easeIn',
			'ease-out'          => 'easeOut',
			'ease-in-out'       => 'easeInOut',
			'ease-in-cubic'     => 'easeInCubic',
			'ease-out-cubic'    => 'easeOutCubic',
			'ease-in-out-cubic' => 'easeInOutCubic',
			'ease-in-circ'      => 'easeInCirc',
			'ease-out-circ'     => 'easeOutCirc',
			'ease-in-out-circ'  => 'easeInOutCirc',
			'ease-in-expo'      => 'easeInExpo',
			'ease-out-expo'     => 'easeOutExpo',
			'ease-in-out-expo'  => 'easeInOutExpo',
			'ease-in-quad'      => 'easeInQuad',
			'ease-out-quad'     => 'easeOutQuad',
			'ease-in-out-quad'  => 'easeInOutQuad',
			'ease-in-quart'     => 'easeInQuart',
			'ease-out-quart'    => 'easeOutQuart',
			'ease-in-out-quart' => 'easeInOutQuart',
			'ease-in-quint'     => 'easeInQuint',
			'ease-out-quint'    => 'easeOutQuint',
			'ease-in-out-quint' => 'easeInOutQuint',
			'ease-in-sine'      => 'easeInSine',
			'ease-out-sine'     => 'easeOutSine',
			'ease-in-out-sine'  => 'easeInOutSine',
			'ease-in-back'      => 'easeInBack',
			'ease-out-back'     => 'easeOutBack',
			'ease-in-out-back'  => 'easeInOutBack',
		];
		return $easings;
	}

	/**
	 * Geeft animatietypes terug
	 *
	 * @return array
	 */
	public static function get_types() {
		$types = [
			'fade'        => __( 'Fade', 'siw' ),
			'slide-up'    => __( 'Slide up', 'siw' ),
			'slide-down'  => __( 'Slide down', 'siw' ),
			'slide-left'  => __( 'Slide left', 'siw' ),
			'slide-right' => __( 'Slide right', 'siw' ),
			'zoom-in'     => __( 'Zoom in', 'siw' ),
			'zoom-out'    => __( 'Zoom out', 'siw' ),
			'flip-up'     => __( 'Flip up', 'siw' ),
			'flip-down'   => __( 'Flip down', 'siw' ),
			'flip-left'   => __( 'Flip left', 'siw' ),
			'flip-right'  => __( 'Flip right', 'siw' ),
		];
		return $types;
	}

}
