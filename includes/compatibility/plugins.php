<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor diverse plugins
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Plugins {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		/* Strong Testimonials */
		add_filter( 'wpmtst_post_type', [ $self, 'set_wpmtst_post_type_slug' ] );
		add_action( 'init', [ $self, 'remove_extra_image_size'] );
		add_filter( 'siw_social_share_post_types', [ $self, 'set_wpmtst_social_share'] );

		/* Limit Login Attempts */
		add_filter( 'limit_login_whitelist_ip', [ $self, 'process_whitelisted_ips'], PHP_INT_MAX, 2 ); 
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
	 * Verwijdert extra image size
	 */
	public function remove_extra_image_size() {
		remove_image_size( 'widget-thumbnail' );
	}

	/**
	 * Undocumented function
	 *
	 * @param array $post_types
	 *
	 * @return array
	 */
	public function set_wpmtst_social_share( array $post_types ) : array {
		$post_types['wpm-testimonial'] = __( 'Deel dit ervaringsverhaal', 'siw' );
		return $post_types;
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
