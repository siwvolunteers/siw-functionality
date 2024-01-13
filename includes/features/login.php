<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Traits\Assets_Handle;
use SIW\Util\CSS;

class Login extends Base {

	use Assets_Handle;

	#[Add_Filter( 'login_headerurl' )]
	private const LOGIN_HEADER_URL = SIW_SITE_URL;

	#[Add_Filter( 'login_headertext' )]
	private const LOGIN_HEADER_TEXT = Properties::NAME;

	#[Add_Action( 'login_enqueue_scripts' )]
	public function enqueue_style() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/login.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::get_assets_handle() );

		$css_generator = CSS::get_css_generator();

		$logo_id = get_theme_mod( 'custom_logo' );
		if ( false !== $logo_id ) {
			$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
			$css_generator->root_variable( 'siw-logo-url', "url('{$logo_url}')" );
		}

		$background_image_id = get_theme_mod( 'siw_login_background_image' );
		if ( false !== $background_image_id ) {
			$background_url = wp_get_attachment_image_url( $background_image_id, 'full' );
			$css_generator->root_variable( 'siw-login-background-url', "url('{$background_url}')" );
		}
		wp_add_inline_style( self::get_assets_handle(), $css_generator->get_output() );
	}

	#[Add_Filter( 'login_message' )]
	public function set_login_message( string $message ): string {
		if ( empty( $message ) ) {
			$message = '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
		}
		return $message;
	}

	#[Add_Action( 'login_head' )]
	public function remove_shake_js() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	#[Add_Action( 'wp_login' )]
	public function log_last_user_login( string $user_login, \WP_User $user ) {
		update_user_meta( $user->ID, 'last_login', current_datetime()->getTimestamp() );
	}

	#[Add_Action( 'customize_register' )]
	public function add_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {

		if ( ! $wp_customize_manager->get_panel( 'siw_panel' ) ) {
			$wp_customize_manager->add_panel(
				'siw_panel',
				[
					'priority' => 25,
					'title'    => __( 'SIW opties', 'siw' ),
				]
			);
		}

		$wp_customize_manager->add_section(
			'siw_login',
			[
				'title'    => __( 'Inlogpagina', 'siw' ),
				'priority' => 10,
				'panel'    => 'siw_panel',
			]
		);

		$wp_customize_manager->add_setting(
			'siw_login_background_image',
			[
				'default' => '',
			]
		);

		$wp_customize_manager->add_control(
			new \WP_Customize_Media_Control(
				$wp_customize_manager,
				'siw_login_background_image',
				[
					'label'     => __( 'Achtergrondafbeelding', 'siw' ),
					'section'   => 'siw_login',
					'mime_type' => 'image',
				]
			)
		);
	}
}
