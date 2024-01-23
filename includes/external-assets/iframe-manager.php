<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://github.com/orestbida/iframemanager
 */
class Iframe_Manager extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return '@orestbida/iframemanager';
	}

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.2.5';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/iframemanager.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/iframemanager.js';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-ENrpu20lejAzN1mxAqS9zWEKqggPt07u1yPO1365JzE=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-l+q6tuz0+Kq5hR0kCsOJB4xp9+Jk/2QxVwyEvE9b7Uc=';
	}
}
