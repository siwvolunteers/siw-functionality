<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://frappe.io/charts
 */
class Frappe_Charts extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.6.2';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'frappe-charts';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/frappe-charts.min.umd.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-77HRXznViw6+XwGfSX2YMIOjsO69g2fFuzKKWgo+X8U=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return null;
	}
}
