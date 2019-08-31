<?php

/**
 * Aanpassingen voor diverse plugins
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		/* WP Pusher */
		add_action( 'wppusher_plugin_was_updated', [ $self, 'process_plugin_update' ] );

		/* Rewrite rules bijwerken */
		add_action( 'siw_update_plugin', [ $self, 'flush_rewrite_rules' ] );

		/* Safe Redirect Manager */
		add_filter( 'srm_max_redirects', [ $self, 'set_max_redirects'] );
		add_filter( 'srm_default_direct_status', [ $self, 'set_default_direct_status'] );
 
		/* Strong Testimonials */
		add_filter( 'wpmtst_post_type', [ $self, 'set_wpmtst_post_type_slug' ] );

		/* WMPL */ 
		add_action( 'widgets_init', [ $self, 'unregister_wpml_widget'], 99 );
		add_action( 'admin_head', [ $self, 'remove_wpml_meta_box'] );

		/* Limit Login Attempts */
		add_filter( 'limit_login_whitelist_ip', [ $self, 'process_whitelisted_ips'], PHP_INT_MAX, 2 ); 

	}

	/**
	 * Zet taak klaar om pluginupdate te verwerken
	 */
	public function process_plugin_update() {
		wp_schedule_single_event( time(), 'siw_update_plugin' );
	}

	/**
	 * Rewrite rules bijwerken
	 */
	public function flush_rewrite_rules() {
		flush_rewrite_rules();
	}

	/**
	 * Past maximaal aantal redirects aan
	 *
	 * @param int $max_redirects
	 * @return int
	 */
	public function set_max_redirects( int $max_redirects ) {
		$max_redirects = 250;
		return $max_redirects;
	}

	/**
	 * Past standaard redirect statuscode aan
	 *
	 * @param int $default_direct_status
	 * @return int
	 */
	public function set_default_direct_status( int $default_direct_status ) {
		$default_direct_status = 301;
		return $default_direct_status;
	}

	/**
	 * Past permalink-slug van ervaringsverhalen aan
	 *
	 * @param array $args
	 * @return array
	 */
	public function set_wpmtst_post_type_slug( array $args ) {
		$args['rewrite']['slug'] = 'ervaring';
		return $args;
	}

	/**
	 * Verwijdert WPML widget
	 */
	public function unregister_wpml_widget() {
		unregister_widget( 'WPML_LS_Widget' );
	}

	/**
	 * Verwijdert WPML meta box
	 */
	public function remove_wpml_meta_box() {
		$screen = get_current_screen();
		remove_meta_box( 'icl_div_config', $screen->post_type, 'normal' );
	}

	/**
	 * Past IP-whitelist toe op Limit Login Attempts
	 *
	 * @param bool $allow
	 * @param string $ip
	 * @return bool
	 */
	public function process_whitelisted_ips( bool $allow, string $ip ) {
		$ip_whitelist = siw_get_option( 'ip_whitelist' );
		if ( is_array( $ip_whitelist ) && in_array( $ip, $ip_whitelist ) ) {
			$allow = true;
		}
		return $allow;
	}
}