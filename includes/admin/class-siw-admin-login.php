<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen aan login
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Admin_Login {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {
		$self = new self();
		add_action( 'login_enqueue_scripts', [ $self, 'enqueue_style' ] );
		add_filter( 'login_headerurl', [ $self, 'set_login_headerurl' ] );
		add_filter( 'login_headertitle', [ $self, 'set_login_headertitle' ] );
		add_filter( 'login_message', [ $self, 'set_login_message' ] );
		add_action( 'login_head', [ $self, 'remove_shake_js'] );
		add_action( 'wp_login', [ $self, 'log_last_user_login'], 10, 2 );
		add_filter( 'manage_users_columns', [ $self, 'add_user_column_last_login' ] );
		add_action( 'manage_users_custom_column', [ $self, 'set_user_column_last_login' ], 10, 3 );
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
	public function set_login_headerurl( $url ) {
		$url = SIW_SITE_URL;
		return $url;
	}

	/**
	 * Zet de title voor het login-logo
	 *
	 * @param string $title
	 * @return string
	 */
	public function set_login_headertitle( $title ) {
		$title = SIW_Properties::NAME;
		return $title;
	}

	/**
	 * Zet de login-boodschap
	 *
	 * @param string $message
	 * @return string
	 */
	public function set_login_message ( $message ) {
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
	public function log_last_user_login( $user_login, $user ) {
		update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
	}

	/**
	 * Voegt kolom met laatste login van een gebruiker toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function add_user_column_last_login( $columns ) {
		$columns['lastlogin'] = __( 'Laatste login', 'siw' );
		return $columns;
	}

	/**
	 * Vult kolom met laatste login van een gebruiker
	 *
	 * @param string $value
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 */
	public function set_user_column_last_login( $value, $column_name, $user_id ) {
		if ( 'lastlogin' == $column_name ) {
			$last_login = get_user_meta( $user_id, 'last_login', true );
			if ( ! empty( $last_login ) ) {
				$time = mysql2date( 'H:i', $last_login, false );
				$date = SIW_Formatting::format_date( mysql2date( 'Y-m-d', $last_login, false ), true );
				$value = $date . ' ' . $time;
			}
			else {
				$value = __( 'Nog nooit ingelogd', 'siw' );
			}
		}
		return $value;	
	}
}
