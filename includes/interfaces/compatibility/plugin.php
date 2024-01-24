<?php declare(strict_types=1);

namespace SIW\Interfaces\Compatibility;

interface Plugin {

	/**
	 * Geeft basename van plugin terug (om te checken of deze actief is)
	 *
	 * @see https://developer.wordpress.org/reference/functions/is_plugin_active/
	 */
	public static function get_plugin_basename(): string;
}
