<?php declare(strict_types=1);

namespace SIW\WooCommerce;

/**
 * Custom templates voor WooCommerce
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Templates {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'wc_get_template', [ $self, 'maybe_set_custom_template' ], 10, 5 );
	}

	/** Overschrijft template */
	public function maybe_set_custom_template( string $template, string $template_name, array $args, string $template_path, string $default_path ): string {
		$custom_template = SIW_TEMPLATES_DIR . 'woocommerce/' . $template_name;
		if ( file_exists( $custom_template ) ) {
			$template = $custom_template;
		}
		return $template;
	}

}
