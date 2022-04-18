<?php declare(strict_types=1);

namespace SIW;

/**
 * Plugin update
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Update {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wppusher_plugin_was_updated', [ $self, 'schedule_plugin_update_hook' ] );
		add_action( 'siw_update_plugin', 'flush_rewrite_rules' );
	}

	/** Zet taak klaar om pluginupdate te verwerken */
	public function schedule_plugin_update_hook() {
		wp_schedule_single_event( time(), 'siw_update_plugin' );
	}
}
