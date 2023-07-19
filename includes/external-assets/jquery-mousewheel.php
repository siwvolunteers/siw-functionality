<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Jquery mousewheel
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/jquery/jquery-mousewheel
 */
class JQuery_Mousewheel extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '3.1.13';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'jquery-mousewheel';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'jquery.mousewheel.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-VSluyclkkEBBFNZ6S8I2Okq/R6W0InHkqdukNreEYOY=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_dependencies(): array {
		return [ 'jquery' ];
	}

}
