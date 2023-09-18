<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Assets\SIW_SVG;
use SIW\Attributes\Action;
use SIW\Base;

/**
 * Class voor SIW icons
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Icons extends Base {

	const ASSETS_HANDLE = 'siw-icons';

	#[Action( 'wp_body_open' )]
	/** Voegt SVG-sprite toe aan header */
	public function add_svg_sprite() {
		printf( '<div data-svg-url="%s" style="display:none;"></div>', esc_url( SIW_ASSETS_URL . 'icons/dashicons.svg' ) );
		printf( '<div data-svg-url="%s" style="display:none;"></div>', esc_url( SIW_ASSETS_URL . 'icons/sdg-icons.svg' ) );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt SVG-script toe */
	public function enqueue_script() {
		wp_enqueue_script( SIW_SVG::ASSETS_HANDLE );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/features/icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
