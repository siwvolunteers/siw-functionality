<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Traits\Assets_Handle;

class Icons extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_body_open' )]
	public function add_svg_sprite() {
		foreach ( $this->get_icon_sets() as $icon_set ) {
			printf( '<div data-svg-url="%s" style="display:none;"></div>', esc_url( SIW_ASSETS_URL . "icons/{$icon_set}.svg" ) );
		}
	}
	protected function get_icon_sets(): array {
		return [
			'sdg-icons',
			'genericons-neue',
			'social-logos',
		];
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_script() {
		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/features/icons.js', null, SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::get_assets_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_style() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
