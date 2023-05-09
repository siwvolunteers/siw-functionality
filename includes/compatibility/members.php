<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor Members
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/members/
 */
class Members extends Base implements I_Plugin {

	#[Filter( 'members_login_widget_enabled' )]
	#[Filter( 'members_users_widget_enabled' )]
	private const WIDGETS_ENABLED = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'members/members.php';
	}

	#[Action( 'init' )]
	/** Verwijdert vraag om reviews */
	public function disable_review_prompt() {
		defined( 'MEMBERS_DISABLE_REVIEW_PROMPT' ) || define( 'MEMBERS_DISABLE_REVIEW_PROMPT', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	}
}
