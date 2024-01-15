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
 * @see       https://generatepress.com/
 */
class GeneratePress extends Base implements I_Plugin {

	#[Add_Filter( 'generate_back_to_top_scroll_speed' )]
	private const BACK_TO_TOP_SCROLL_SPEED = 500;

	#[Add_Filter( 'generate_font_manager_show_google_fonts' )]
	private const SHOW_GOOGLE_FONTS = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'gp-premium/gp-premium.php';
	}

	#[Add_Action( 'init' )]
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	#[Add_Action( 'generate_archive_title', 1 ) ]
	public function remove_archive_title() {
		remove_action( 'generate_archive_title', 'generate_archive_title' );
	}

	#[Add_Filter( 'generate_logo_attributes' )]
	public function set_skip_lazy_for_logo( array $attributes ): array {
		$attributes['data-skip-lazy'] = '1';
		return $attributes;
	}

	#[Add_Filter( 'generate_elements_custom_args' )]
	public function set_elements_orderby( array $args ): array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	#[Add_Filter( 'generate_copyright' )]
	public function set_copyright_message(): string {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	#[Add_Filter( 'generate_default_color_palettes' )]
	public function set_default_color_palettes(): array {
		return array_column(
			CSS::get_colors(),
			'color'
		);
	}

	#[Add_Action( Update::PLUGIN_UPDATED_HOOK, 1 )]
	#[Add_Action( 'customize_save_after', 1 )]
	public function set_global_colors() {
		$generate_settings = get_option( 'generate_settings', [] );
		$generate_settings['global_colors'] = CSS::get_colors();
		update_option( 'generate_settings', $generate_settings );
	}

	#[Add_Action( 'wp_enqueue_scripts', PHP_INT_MAX )]
	public function dequeue_secondary_nav_mobile() {
		wp_dequeue_style( 'generate-secondary-nav-mobile' );
	}

	#[Add_Action( 'wp_enqueue_scripts', PHP_INT_MAX )]
	public function add_404_style() {
		if ( ! is_404() ) {
			return;
		}

		$background_image = get_theme_mod( 'siw_404_background_image' );
		if ( false === $background_image ) {
			return;
		}

		$css = new \GeneratePress_Backgrounds_CSS();
		$background_size = get_theme_mod( 'siw_404_background_size' );
		$background_size = ( '100' === $background_size ) ? '100% auto' : esc_attr( $background_size );

		$css->set_selector( '.error404 .container' );
		$css->add_property( 'max-width', 'unset' );
		$css->add_property( 'height', '70vh' );
		$css->add_property( 'background-image', esc_url( $background_image ), 'url' );
		$css->add_property( 'background-repeat', esc_attr( get_theme_mod( 'siw_404_background_repeat' ) ) );
		$css->add_property( 'background-size', esc_attr( $background_size ) );
		$css->add_property( 'background-attachment', esc_attr( get_theme_mod( 'siw_404_background_attachment' ) ) );
		$css->add_property( 'background-position', esc_attr( get_theme_mod( 'siw_404_background_position' ) ) );

		$css->set_selector( '.error404 .container .site-content' );
		$css->add_property( 'text-align', 'center' );

		$css->set_selector( '.error404 .container .site-content main' );
		$css->add_property( 'padding', '50px' );
		$css->add_property( 'max-width', '85ch' );
		$css->add_property( 'display', 'inline-block' );
		$css->add_property( 'background-color', 'var(--siw-base)' );
		wp_add_inline_style( 'generate-style', $css->css_output() );
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

	#[Add_Action( 'customize_register', PHP_INT_MAX )]
	public function add_woocommerce_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {
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

	#[Add_Action( 'customize_register', PHP_INT_MAX )]
	public function add_backgrounds_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {
		if ( ! $wp_customize_manager->get_panel( 'generate_backgrounds_panel' ) ) {
			return;
		}

		$wp_customize_manager->add_section(
			'siw_backgrounds_404',
			[
				'title'    => __( '404-pagina', 'siw' ),
				'priority' => 20,
				'panel'    => 'generate_backgrounds_panel',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_background_image',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_background_repeat',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_background_size',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_background_attachment',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_background_position',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);

		$wp_customize_manager->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize_manager,
				'siw_404_background_image',
				[
					'label'   => __( 'Achtergrondafbeelding', 'siw' ),
					'section' => 'siw_backgrounds_404',
				]
			)
		);
		$wp_customize_manager->add_control(
			new \GeneratePress_Background_Images_Customize_Control(
				$wp_customize_manager,
				'siw_404_background_controls',
				[
					'section'  => 'siw_backgrounds_404',
					'settings' => [
						'repeat'     => 'siw_404_background_repeat',
						'size'       => 'siw_404_background_size',
						'attachment' => 'siw_404_background_attachment',
						'position'   => 'siw_404_background_position',
					],
				]
			)
		);
	}

	#[Add_Filter( 'generate_blog_columns' )]
	public function disable_404_columns( bool $columns ): bool {
		if ( is_404() ) {
			return false;
		}
		return $columns;
	}
}
