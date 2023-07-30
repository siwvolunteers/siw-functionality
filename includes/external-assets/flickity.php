<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Flickity carousel
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://flickity.metafizzy.co/
 */
class Flickity extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'flickity';
	}

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '2.3.0';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/flickity.pkgd.min.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'css/flickity.min.css';
	}


	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-OqbVjZdNBS1rrUlOFb/xA8UY4UjlkFTABlZGELQRA9I=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-ju8u9s+ILV4ukWfLfIsOu+t1soppiDVIhzPRSTJvq08=';
	}
}
