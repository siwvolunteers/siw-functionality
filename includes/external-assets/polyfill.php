<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\External_Assets\External_Asset;


/**
 * Polyfill.io
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://polyfill.io/v3/
 */
class Polyfill extends External_Asset {

	/** Features voor Polyfill.io */
	protected static array $polyfill_features = [
		'default',
	];

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '3.111.0';
	}

	/** {@inheritDoc} */
	protected static function get_script_url(): ?string {
		return add_query_arg(
			[
				'version'  => static::get_version_number(),
				'features' => implode( ',', static::$polyfill_features ), // TODO: filter?
				'flags'    => 'gated',
			],
			'https://polyfill.io/v3/polyfill.min.js'
		);
	}

	/** {@inheritDoc} */
	protected static function get_style_url(): ?string {
		return null;
	}

}