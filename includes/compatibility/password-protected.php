<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor "Password protected" plugin
 *
 * - Welkomstboodschap
 * - Verwijderen shake-animatie
 * - IP whitelist voor directe toegang tot site
 * - Secure cookie
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/password-protected/
 * @since     3.0.0
 */
class Password_Protected { 

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\Password_Protected' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'password_protected_before_login_form', [ $self, 'set_login_message' ] );
		add_action( 'password_protected_login_head', [ $self, 'remove_shake_js'] );
		add_filter( 'password_protected_is_active', [ $self, 'process_whitelisted_ips' ] );
		add_filter( 'password_protected_secure_password_protected_cookie', [ $self, 'set_secure_cookie'], 10, 2 );
	}

	/**
	 * Toont melding over testsite
	 */
	public function set_login_message() {
		$site_url = 'https://www.siw.nl';
		?>
		<p class="message">
		<b><?php esc_html_e( 'Welkom op de testsite van SIW.','siw' )?></b><br />
		<?php esc_html_e( 'Voer het wachtwoord in om toegang te krijgen.', 'siw' )?><br /><br />
		<?php printf( wp_kses_post( __( 'Klik <a href="%s">hier</a> om naar de echte website van SIW te gaan.', 'siw' ) ), esc_url( $site_url ) );?>
		</p>
	<?php
	}

	/**
	 * Verwijdert shake animatie
	 */
	public function remove_shake_js() {
		remove_action( 'password_protected_login_head', 'wp_shake_js', 12 );
	}
	
	/**
	 * Verwerkt gewhiteliste IP-adressen voor directe toegang tot de site
	 *
	 * @param bool $is_active
	 * @return bool
	 */
	public function process_whitelisted_ips( bool $is_active ) {
		$ip_whitelist = siw_get_option('ip_whitelist');
		if ( is_array( $ip_whitelist ) && isset( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], $ip_whitelist ) ) {
			$is_active = false;
		}
		return $is_active;
	}

	/**
	 * Zet secure cookie als verbinding secure is
	 *
	 * @param bool $secure_cookie
	 * @param bool $secure_connection
	 * @return bool
	 */
	public function set_secure_cookie( bool $secure_cookie, bool $secure_connection ) {
		return $secure_connection;
	}
}
