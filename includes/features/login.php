<?php declare(strict_types=1);

namespace SIW\Features;

use luizbills\CSS_Generator\Generator;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Traits\Class_Assets;

class Login extends Base {

	use Class_Assets;

	#[Add_Filter( 'login_headerurl' )]
	private const LOGIN_HEADER_URL = SIW_SITE_URL;

	#[Add_Filter( 'login_headertext' )]
	private const LOGIN_HEADER_TEXT = Properties::NAME;

	#[Add_Action( 'login_enqueue_scripts' )]
	public function enqueue_style() {
		self::enqueue_class_style();

		$css_generator = new Generator();

		$logo_id = get_theme_mod( 'custom_logo' );
		if ( false !== $logo_id ) {
			$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
			$css_generator->add_rule(
				'html body.login h1 a',
				[
					'background-image' => sprintf( 'url(%s)', esc_url( $logo_url ) ),
				]
			);
		}

		$background_image = get_theme_mod( 'siw_login_background_image' );
		if ( false !== $background_image ) {
			$background_size = get_theme_mod( 'siw_login_background_size' );
			$background_size = ( '100' === $background_size ) ? '100% auto' : esc_attr( $background_size );

			$css_generator->add_rule(
				'html',
				[
					'background-image'      => sprintf( 'url(%s)', esc_url( $background_image ) ),
					'background-repeat'     => esc_attr( get_theme_mod( 'siw_login_background_repeat' ) ),
					'background-size'       => esc_attr( $background_size ),
					'background-attachment' => esc_attr( get_theme_mod( 'siw_login_background_attachment' ) ),
					'background-position'   => esc_attr( get_theme_mod( 'siw_login_background_position' ) ),
				]
			);
		}

		wp_add_inline_style( self::get_asset_handle(), $css_generator->get_output() );
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

	#[Add_Action( 'customize_register', PHP_INT_MAX )]
	public function add_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {
		if ( ! $wp_customize_manager->get_panel( 'generate_backgrounds_panel' ) ) {
			return;
		}

		$wp_customize_manager->add_section(
			'siw_backgrounds_login',
			[
				'title'    => __( 'Login pagina', 'siw' ),
				'priority' => 20,
				'panel'    => 'generate_backgrounds_panel',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_login_background_image',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_login_background_repeat',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_login_background_size',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_login_background_attachment',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_login_background_position',
			[
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
			]
		);

		$wp_customize_manager->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize_manager,
				'siw_login_background_image',
				[
					'label'   => __( 'Achtergrondafbeelding', 'siw' ),
					'section' => 'siw_backgrounds_login',
				]
			)
		);
		$wp_customize_manager->add_control(
			new \GeneratePress_Background_Images_Customize_Control(
				$wp_customize_manager,
				'siw_login_background_controls',
				[
					'section'  => 'siw_backgrounds_login',
					'settings' => [
						'repeat'     => 'siw_login_background_repeat',
						'size'       => 'siw_login_background_size',
						'attachment' => 'siw_login_background_attachment',
						'position'   => 'siw_login_background_position',
					],
				]
			)
		);
	}
}
