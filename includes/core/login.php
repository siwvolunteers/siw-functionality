<?php declare(strict_types=1);

namespace SIW\Core;

use SIW\Properties;

/**
 * Aanpassingen aan login
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Login {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'login_enqueue_scripts', [ $self, 'enqueue_style' ] );
		add_filter( 'login_headerurl', fn() : string => SIW_SITE_URL );
		add_filter( 'login_headertext', fn() : string => Properties::NAME );
		add_filter( 'login_message', [ $self, 'set_login_message' ] );
		add_action( 'login_head', [ $self, 'remove_shake_js'] );
		add_action( 'wp_login', [ $self, 'log_last_user_login'], 10, 2 );
	}

	/** Voegt de styling voor de login toe */
	public function enqueue_style() {
		wp_register_style( 'siw-login-css', SIW_ASSETS_URL . 'css/siw-login.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-login-css' );
	}

	/** Zet de login-boodschap */
	public function set_login_message ( string $message ): string {
		if ( empty( $message ) ) {
			$message = '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
		}
		return $message;
	}

	/** Verwijdert de shake-animatie */
	public function remove_shake_js() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	/** Legt laatste login van een gebruiker vast */
	public function log_last_user_login( string $user_login, \WP_User $user ) {
		update_user_meta( $user->ID, 'last_login', current_time( 'timestamp' ) );
	}
}
