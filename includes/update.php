<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Action;

/**
 * Plugin update
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Update extends Base {

	const PLUGIN_UPDATED_HOOK = 'siw_update_plugin';

	#[Action( 'wppusher_plugin_was_updated' )]
	/** Zet taak klaar om pluginupdate te verwerken */
	public function schedule_plugin_update_hook() {
		as_enqueue_async_action( self::PLUGIN_UPDATED_HOOK );
	}
}
