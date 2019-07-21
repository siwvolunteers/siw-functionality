<?php

/**
 * Aanpassingen aan login
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Login {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'login_enqueue_scripts', [ $self, 'enqueue_style' ] );
		add_filter( 'login_headerurl', [ $self, 'set_login_headerurl' ] );
		add_filter( 'login_headertext', [ $self, 'set_login_headertext' ] );
		add_filter( 'login_message', [ $self, 'set_login_message' ] );
		add_action( 'login_head', [ $self, 'remove_shake_js'] );
		add_action( 'wp_login', [ $self, 'log_last_user_login'], 10, 2 );

	}

	/**
	 * Voegt de styling voor de login toe
	 */
	public function enqueue_style() {
		wp_register_style( 'siw-login-css', SIW_ASSETS_URL . 'css/siw-login.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-login-css' );
	}

	/**
	 * Zet de url voor het login-logo
	 *
	 * @param string $url
	 * @return string
	 */
	public function set_login_headerurl( string $url ) {
		$url = SIW_SITE_URL;
		return $url;
	}

	/**
	 * Zet de title voor het login-logo
	 *
	 * @param string $title
	 * @return string
	 */
	public function set_login_headertext( string $title ) {
		$title = SIW_Properties::NAME;
		return $title;
	}

	/**
	 * Zet de login-boodschap
	 *
	 * @param string $message
	 * @return string
	 */
	public function set_login_message ( string $message ) {
		if ( empty( $message ) ) {
			$message = '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
		}
		return $message;
	}

	/**
	 * Verwijdert de shake-animatie
	 */
	public function remove_shake_js() {
		remove_action( 'login_head', 'wp_shake_js', 12 );
	}

	/**
	 * Legt laatste login van een gebruiker vast
	 *
	 * @param string $user_login
	 * @param WP_User $user
	 */
	public function log_last_user_login( string $user_login, WP_User $user ) {
		update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
	}
}