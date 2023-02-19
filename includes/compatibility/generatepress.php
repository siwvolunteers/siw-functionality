<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Update;
use SIW\Util\CSS;

/**
 * Aanpassingen voor GeneratePress
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://generatepress.com/
 */
class GeneratePress extends Base {

	#[Filter( 'generate_back_to_top_scroll_speed' )]
	/** Snelheid voor scroll to top */
	private const BACK_TO_TOP_SCROLL_SPEED = 500;

	#[Filter( 'generate_font_manager_show_google_fonts' )]
	private const SHOW_GOOGLE_FONTS = false;

	#[Filter( 'generate_woocommerce_menu_item_location' )]
	private const WOOCOMMERCE_CART_MENU_ITEM_LOCATION = 'secondary'; // TODO: customizer setting van maken

	#[Action( 'init' )]
	/** Voeg menu order toe een GP Elements */
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	#[Filter( 'generate_elements_custom_args' )]
	/** Sorteer elements standaard op menu_order */
	public function set_elements_orderby( array $args ): array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	#[Filter( 'generate_copyright' )]
	/** Zet copyright voor footer */
	public function set_copyright_message(): string {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	#[Filter( 'generate_default_color_palettes' )]
	/** Zet default kleurenpalet */
	public function set_default_color_palettes(): array {
		return [
			CSS::CONTRAST_COLOR,
			CSS::CONTRAST_COLOR_LIGHT,
			CSS::BASE_COLOR,
			CSS::ACCENT_COLOR,
		];
	}

	#[Action( Update::PLUGIN_UPDATED_HOOK, 1 )]
	#[Action( 'customize_save_after', 1 )]
	/** Zet global colors */
	public function set_global_colors() {
		$generate_settings = get_option( 'generate_settings', [] );
		$generate_settings['global_colors'] = [
			[
				'name'  => 'Accent',
				'slug'  => 'siw-accent',
				'color' => CSS::ACCENT_COLOR,
			],
			[
				'name'  => 'Contrast',
				'slug'  => 'siw-contrast',
				'color' => CSS::CONTRAST_COLOR,
			],
			[
				'name'  => 'Contrast 2',
				'slug'  => 'siw-contrast-light',
				'color' => CSS::CONTRAST_COLOR_LIGHT,
			],
			[
				'name'  => 'Base',
				'slug'  => 'siw-base',
				'color' => CSS::BASE_COLOR,
			],
		];
		update_option( 'generate_settings', $generate_settings );
	}

	#[Action( 'wp_enqueue_scripts', PHP_INT_MAX )]
	public function dequeue_secondary_nav_mobile() {
		wp_dequeue_style( 'generate-secondary-nav-mobile' );
	}

	#[Action( 'customize_controls_enqueue_scripts' )]
	public function add_customizer_script() {
		wp_register_script( 'gp-customizer', SIW_ASSETS_URL . 'js/admin/gp-customizer.js', [], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'gp-customizer' );
	}

	#[Action( Update::PLUGIN_UPDATED_HOOK )]
	/** Update GeneratePress dynamic css cache */
	public function update_dynamic_css() {
		generate_update_dynamic_css_cache();
	}
}
