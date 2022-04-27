<?php declare(strict_types=1);

namespace SIW;

/**
 * Plugin update
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Update {

	const PLUGIN_UPDATED_HOOK = 'siw_update_plugin';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wppusher_plugin_was_updated', [ $self, 'schedule_plugin_update_hook' ] );
	}

	/** Zet taak klaar om pluginupdate te verwerken */
	public function schedule_plugin_update_hook() {
		wp_schedule_single_event( time(), self::PLUGIN_UPDATED_HOOK );
	}
}
