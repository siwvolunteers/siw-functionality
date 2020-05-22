<?php

namespace SIW\Core;

/**
 * Plugin update
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wppusher_plugin_was_updated', [ $self, 'schedule_plugin_update_hook' ] );
		add_action( 'siw_update_plugin', [ $self, 'flush_rewrite_rules' ] );
	}

	/**
	 * Zet taak klaar om pluginupdate te verwerken
	 */
	public function schedule_plugin_update_hook() {
		wp_schedule_single_event( time(), 'siw_update_plugin' );
	}

	/**
	 * Rewrite rules flushen
	 */
	public function flush_rewrite_rules() {
		flush_rewrite_rules();
	}

}
