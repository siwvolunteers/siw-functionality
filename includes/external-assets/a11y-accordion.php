<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * AcceDe Web accordion
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/AcceDe-Web/accordion
 */
class A11Y_Accordion extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.1.0';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return '@accede-web/accordion';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/accordion.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-+vsYFOCO32TdglP9UMOo9+QQbtADwB7prYZXTTZlsRM=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return null;
	}
}
