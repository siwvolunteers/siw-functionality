<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\External_Assets\External_Asset;

/**
 * @see https://polyfill.io/v3/
 */
class Polyfill extends External_Asset {

	protected static array $polyfill_features = [
		'default',
	];

	#[\Override]
	protected static function get_version_number(): ?string {
		return '3.111.0';
	}

	#[\Override]
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
}
