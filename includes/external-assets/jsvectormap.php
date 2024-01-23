<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\External_Assets\NPM_Asset;

/**
 * @see https://github.com/themustafaomar/jsvectormap
 */
class Jsvectormap extends NPM_Asset {

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
		return 'dist/js/jsvectormap.min.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/css/jsvectormap.min.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=';
	}
}
