<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\External_Assets\NPM_Asset;

/**
 * Jsvectormap
 *
 * @see https://github.com/themustafaomar/jsvectormap
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Jsvectormap_World_Map extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.5.3';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'jsvectormap';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/maps/world.js';
	}

	/** {@inheritDoc} */
	protected static function get_script_dependencies(): array {
		return [ Jsvectormap::get_assets_handle() ];
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return null;
	}

}
