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
}
