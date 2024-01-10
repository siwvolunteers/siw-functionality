<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor Safe Redirect Manager
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/safe-redirect-manager/
 */
class Safe_Redirect_Manager extends Base implements I_Plugin {

	#[Add_Filter( 'srm_default_direct_status' )]
	private const DEFAULT_DIRECT_STATUS = \WP_Http::MOVED_PERMANENTLY;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'safe-redirect-manager/safe-redirect-manager.php';
	}
}
