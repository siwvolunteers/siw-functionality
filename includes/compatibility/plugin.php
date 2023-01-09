<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Base;

/**
 * TODO:
 *
 * @copyright   2022 SIW Internationale Vrijwilligersprojecten
 */
abstract class Plugin extends Base {

	/** {@inheritDoc} */
	public static function init( object ...$args ): static {

		if ( ! is_plugin_active( static::get_plugin_path() ) ) {
			return new static(); // TODO: init nullable maken
		}

		return parent::init( ...$args );
	}

	/** Geeft plugin path terug */
	abstract protected static function get_plugin_path(): string;
}
