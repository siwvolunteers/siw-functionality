<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Traits\Class_Assets;

class Icons extends Base {

	use Class_Assets;

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
			'dashicons',
		];
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_script() {
		wp_register_script( self::get_asset_handle(), self::get_script_asset_url(), null, SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::get_asset_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_style() {
		self::enqueue_class_style();
	}
}
