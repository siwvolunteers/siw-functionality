<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * AcceDe Web accordion
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/AcceDe-Web/tablist
 */
class A11Y_Tablist extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '2.0.1';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return '@accede-web/tablist';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/tablist.min.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-TTh9248AuUOCjctFuKmzbxBCt1dEJ58c394ePzRePxc=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return null;
	}

}
