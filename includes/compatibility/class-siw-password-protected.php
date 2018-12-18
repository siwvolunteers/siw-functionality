<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor "Password protected" plugin
 *
 * - Welkomstboodschap
 * - Verwijderen shake-animatie
 * - IP whitelist voor directe toegang tot site
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Password_Protected { 

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! class_exists( 'Password_Protected' ) ) {
			return;
		}
		
		$self = new self();
		add_filter( 'password_protected_before_login_form', [ $self, 'set_login_message' ] );
		add_action( 'password_protected_login_head', [ $self, 'remove_shake_js'] );
		add_filter( 'password_protected_is_active', [ $self, 'process_whitelisted_ips' ] );
	}

	/**
	 * Toont melding over testsite
	 *
	 * @return void
	 */
	public function set_login_message() {
		$site_url = 'https://www.siw.nl';
		?>
		<p class="message">
		<b><?= esc_html__( 'Welkom op de testsite van SIW.','siw' )?></b><br />
		<?= esc_html__( 'Voer het wachtwoord in om toegang te krijgen.', 'siw' )?><br /><br />
		<?= sprintf( wp_kses_post( __( 'Klik <a href="%s">hier</a> om naar de echte website van SIW te gaan.', 'siw' ) ), esc_url( $site_url ) );?>
		</p>
	<?php
	}

	/**
	 * Verwijdert shake animatie
	 *
	 * @return void
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
	public function process_whitelisted_ips( $is_active ) {
		$ip_whitelist = siw_get_ip_whitelist();
		if ( in_array( $_SERVER['REMOTE_ADDR'], $ip_whitelist ) ) {
			$is_active = false;
		}
		return $is_active;
	}
}
