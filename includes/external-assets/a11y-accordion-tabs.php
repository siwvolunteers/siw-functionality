<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see       https://matthiasott.github.io/a11y-accordion-tabs/
 */
class A11Y_Accordion_Tabs extends NPM_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return '1.0.2';
	}

	#[\Override]
	protected static function get_npm_package(): string {
		return 'a11y-accordion-tabs';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'a11y-accordion-tabs.min.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_script_sri(): ?string {
		return 'sha256-uGbMykAbHLkb2leqh8kLzAa8q6W3S61uDchk44M+rT4=';
	}

	#[\Override]
	protected static function get_style_sri(): ?string {
		return null;
	}
}
