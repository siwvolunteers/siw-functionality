<?php

/**
* Aanpassingen voor Meta Box
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat_Meta_Box {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( 'MBAIO\Loader' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'mb_aio_extensions', [ $self, 'select_extensions'] );
		add_filter( 'mb_aio_show_settings', '__return_false' );
		add_action( 'admin_init', [ $self, 'remove_dashboard_widget' ] );
	}

	/**
	 * Selecteert de gebruikte extensies
	 *
	 * @param array $extensions
	 * @return array
	 */
	public function select_extensions( array $extensions ) {
		$extensions = [
			'mb-admin-columns',
			'mb-settings-page',
			'meta-box-columns',
			'meta-box-conditional-logic',
			'meta-box-geolocation',
			'meta-box-group',
			'meta-box-include-exclude',
			'meta-box-tabs',
			'meta-box-text-limiter',
		];
		return $extensions;
	}

	/**
	 * Verwijdert dashboard widget
	 */
	public function remove_dashboard_widget() {
		remove_meta_box( 'meta_box_dashboard_widget', 'dashboard', 'normal' );
	}
}
