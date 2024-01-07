<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Traits\Assets_Handle;
use SIW\Util\CSS;

/**
 * Breadcrumbs
 *
 * @see https://docs.wpslimseo.com/slim-seo/breadcrumbs/
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Breadcrumbs extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/breadcrumbs.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/features/breadcrumbs.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

	#[Add_Action( 'generate_before_main_content', 1 ) ]
	/** Genereer breadcrumbs */
	public function generate_crumbs(): void {

		if ( is_front_page() || is_404() ) {
			return;
		}

		echo do_shortcode( '<div class="' . CSS::HIDE_ON_MOBILE_CLASS . '">[slim_seo_breadcrumbs separator="&rsaquo;"]</div>' );
	}
}
