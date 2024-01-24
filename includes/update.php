<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Add_Action;

class Update extends Base {

	public const PLUGIN_UPDATED_HOOK = 'siw_update_plugin';

	#[Add_Action( 'wppusher_plugin_was_updated' )]
	/** Zet taak klaar om pluginupdate te verwerken */
	public function schedule_plugin_update_hook() {
		as_enqueue_async_action( self::PLUGIN_UPDATED_HOOK );
	}
}
