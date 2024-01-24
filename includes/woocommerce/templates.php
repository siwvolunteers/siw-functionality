<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Templates extends Base {

	#[Add_Filter( 'wc_get_template' )]
	public function maybe_set_custom_template( string $template, string $template_name, array $args, string $template_path, string $default_path ): string {
		$custom_template = SIW_TEMPLATES_DIR . 'woocommerce/' . $template_name;
		if ( file_exists( $custom_template ) ) {
			$template = $custom_template;
		}
		return $template;
	}
}
