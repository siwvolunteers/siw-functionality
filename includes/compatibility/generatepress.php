<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;
use SIW\Properties;
use SIW\Update;
use SIW\Util\CSS;

/**
 * Aanpassingen voor GeneratePress
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://generatepress.com/
 */
class GeneratePress extends Base implements I_Plugin {

	#[Add_Filter( 'generate_back_to_top_scroll_speed' )]
	/** Snelheid voor scroll to top */
	private const BACK_TO_TOP_SCROLL_SPEED = 500;

	#[Add_Filter( 'generate_font_manager_show_google_fonts' )]
	private const SHOW_GOOGLE_FONTS = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'gp-premium/gp-premium.php';
	}

	#[Add_Action( 'init' )]
	/** Voeg menu order toe een GP Elements */
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	#[Add_Action( 'generate_archive_title', 1 ) ]
	public function remove_archive_title() {
		remove_action( 'generate_archive_title', 'generate_archive_title' );
	}

	#[Add_Filter( 'generate_elements_custom_args' )]
	/** Sorteer elements standaard op menu_order */
	public function set_elements_orderby( array $args ): array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	#[Add_Filter( 'generate_copyright' )]
	/** Zet copyright voor footer */
	public function set_copyright_message(): string {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	#[Add_Filter( 'generate_default_color_palettes' )]
	/** Zet default kleurenpalet */
	public function set_default_color_palettes(): array {
		return array_column(
			CSS::get_colors(),
			'color'
		);
	}

	#[Add_Action( Update::PLUGIN_UPDATED_HOOK, 1 )]
	#[Add_Action( 'customize_save_after', 1 )]
	/** Zet global colors */
	public function set_global_colors() {
		$generate_settings = get_option( 'generate_settings', [] );
		$generate_settings['global_colors'] = CSS::get_colors();
		update_option( 'generate_settings', $generate_settings );
	}

	#[Add_Action( 'wp_enqueue_scripts', PHP_INT_MAX )]
	public function dequeue_secondary_nav_mobile() {
		wp_dequeue_style( 'generate-secondary-nav-mobile' );
	}

	#[Add_Action( Update::PLUGIN_UPDATED_HOOK )]
	/** Update GeneratePress dynamic css cache */
	public function update_dynamic_css() {
		generate_update_dynamic_css_cache();
	}

	#[Add_Filter( 'generate_woocommerce_menu_item_location' )]
	public function set_woocommerce_menu_item_location() {
		return generatepress_wc_get_setting( 'cart_menu_item_location' );
	}

	#[Add_Filter( 'generate_woocommerce_defaults' )]
	public function generate_woocommerce_defaults( array $defaults ): array {
		$defaults['cart_menu_item_location'] = 'primary';
		return $defaults;
	}

	#[Add_Action( 'customize_register' )]
	public function add_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {
		$defaults = generatepress_wc_defaults();

		$wp_customize_manager->add_control(
			new \GeneratePress_Title_Customize_Control(
				$wp_customize_manager,
				'siw_woocommerce_general_title',
				[
					'section'  => 'generate_woocommerce_layout',
					'type'     => 'generatepress-customizer-title',
					'title'    => __( 'Extra', 'siw' ),
					'settings' => ( isset( $wp_customize_manager->selective_refresh ) ) ? [] : 'blogname',
				]
			)
		);

		$wp_customize_manager->add_setting(
			'generate_woocommerce_settings[cart_menu_item_location]',
			[
				'default'           => $defaults['cart_menu_item_location'],
				'type'              => 'option',
				'sanitize_callback' => 'generate_premium_sanitize_choices',
			]
		);

		$wp_customize_manager->add_control(
			'generate_woocommerce_settings[cart_menu_item_location]',
			[
				'type'            => 'select',
				'label'           => __( 'Winkelmand menu item', 'siw' ),
				'section'         => 'generate_woocommerce_layout',
				'choices'         => [
					'primary'   => __( 'Primaire menu', 'siw' ),
					'secondary' => __( 'Secundaire menu', 'siw' ),
				],
				'settings'        => 'generate_woocommerce_settings[cart_menu_item_location]',
				'active_callback' => 'generatepress_wc_menu_cart_active',
				'priority'        => 11,
			]
		);
	}
}
