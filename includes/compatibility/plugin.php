<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Base;

abstract class Plugin extends Base {

	#[\Override]
	protected static function should_load(): bool {
		return is_plugin_active( static::get_plugin_basename() );
	}

	/**
	 * Geeft basename van plugin terug (om te checken of deze actief is)
	 *
	 * @see https://developer.wordpress.org/reference/functions/is_plugin_active/
	 */
	abstract public static function get_plugin_basename(): string;
}
