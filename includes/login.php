<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Properties;
use SIW\Util\CSS;

/**
 * Aanpassingen aan login
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Login extends Base {

	private const ASSETS_HANDLE = 'siw-login-css';

	#[Add_Filter( 'login_headerurl' )]
	private const LOGIN_HEADER_URL = SIW_SITE_URL;

	#[Add_Filter( 'login_headertext' )]
	private const LOGIN_HEADER_TEXT = Properties::NAME;

	#[Add_Action( 'login_enqueue_scripts' )]
	/** Voegt de styling voor de login toe */
	public function enqueue_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/login.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );

		$logo_url = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
		$css = CSS::get_css_generator()->root_variable( 'siw-logo-url', "url('{$logo_url}')" )->get_output();
		wp_add_inline_style( self::ASSETS_HANDLE, $css );
	}

	#[Add_Filter( 'login_message' )]
	/** Zet de login-boodschap */
	public function set_login_message( string $message ): string {
		if ( empty( $message ) ) {
			$message = '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
		}
		return $message;
	}

	#[Add_Action( 'login_head' )]
	/** Verwijdert de shake-animatie */
	public function remove_shake_js() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	#[Add_Action( 'wp_login' )]
	/** Legt laatste login van een gebruiker vast */
	public function log_last_user_login( string $user_login, \WP_User $user ) {
		update_user_meta( $user->ID, 'last_login', current_datetime()->getTimestamp() );
	}
}
