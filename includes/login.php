<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Properties;

/**
 * Aanpassingen aan login
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Login extends Base {

	const ASSETS_HANDLE = 'siw-login-css';

	#[Filter( 'login_headerurl' )]
	const LOGIN_HEADER_URL = SIW_SITE_URL;

	#[Filter( 'login_headertext' )]
	const LOGIN_HEADER_TEXT = Properties::NAME;

	#[Action( 'login_enqueue_scripts' )]
	/** Voegt de styling voor de login toe */
	public function enqueue_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/siw-login.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	#[Filter( 'login_message' )]
	/** Zet de login-boodschap */
	public function set_login_message( string $message ): string {
		if ( empty( $message ) ) {
			$message = '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
		}
		return $message;
	}

	#[Action( 'login_head' )]
	/** Verwijdert de shake-animatie */
	public function remove_shake_js() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	#[Action( 'wp_login' )]
	/** Legt laatste login van een gebruiker vast */
	public function log_last_user_login( string $user_login, \WP_User $user ) {
		update_user_meta( $user->ID, 'last_login', current_time( 'timestamp' ) );
	}
}
