<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Visibility_Class;
use SIW\Traits\Class_Assets;

/**
 * @see https://docs.wpslimseo.com/slim-seo/breadcrumbs/
 */
class Breadcrumbs extends Base {

	use Class_Assets;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}

	#[Add_Action( 'generate_before_main_content', 1 ) ]
	public function generate_crumbs(): void {

		if ( is_front_page() || is_404() ) {
			return;
		}

		echo do_shortcode( '<div class="' . Visibility_Class::HIDE_ON_MOBILE->value . '">[slim_seo_breadcrumbs separator="&rsaquo;"]</div>' );
	}
}
