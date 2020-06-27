<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor "Password protected" plugin
 *
 * - Welkomstboodschap
 * - Verwijderen shake-animatie
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
	 * Zet secure cookie als verbinding secure is
	 *
	 * @param bool $secure_cookie
	 * @param bool $secure_connection
	 * @return bool
	 */
	public function set_secure_cookie( bool $secure_cookie, bool $secure_connection ) : bool {
		return $secure_connection;
	}
}
