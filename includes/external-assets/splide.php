<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Splide
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://splidejs.com
 */
class Splide extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '4.1.4';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return '@splidejs/splide';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/js/splide.min.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/css/splide.min.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-FZsW7H2V5X9TGinSjjwYJ419Xka27I8XPDmWryGlWtw=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-5uKiXEwbaQh9cgd2/5Vp6WmMnsUr3VZZw0a8rKnOKNU=';
	}
}
