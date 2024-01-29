<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://frappe.io/charts
 */
class Frappe_Charts extends NPM_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return '1.6.2';
	}

	#[\Override]
	protected static function get_npm_package(): string {
		return 'frappe-charts';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/frappe-charts.min.umd.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_script_sri(): ?string {
		return 'sha256-77HRXznViw6+XwGfSX2YMIOjsO69g2fFuzKKWgo+X8U=';
	}

	#[\Override]
	protected static function get_style_sri(): ?string {
		return null;
	}
}
