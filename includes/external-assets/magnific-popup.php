<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Magnific Popup
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/dimsemenov/Magnific-Popup
 */
class Magnific_Popup extends NPM_Asset {


	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.1.0';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'magnific-popup';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/jquery.magnific-popup.min.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/magnific-popup.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-P93G0oq6PBPWTP1IR8Mz/0jHHUpaWL0aBJTKauisG7Q=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-RdH19s+RN0bEXdaXsajztxnALYs/Z43H/Cdm1U4ar24=';
	}
}
