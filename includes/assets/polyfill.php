<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;

/**
 * Polyfill.io
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://polyfill.io/v3/
 */
class Polyfill implements Script, External {

	/** Versienummer */
	const VERSION = '3.108.0';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'polyfill';

	/** Features voor Polyfill.io */
	protected array $polyfill_features = [
		'default'
	];

	/** {@inheritDoc} */
	public function register_script() {
		$polyfill_url = add_query_arg(
			[
				'version'  => self::VERSION,
				'features' => implode( ',', $this->polyfill_features ), //TODO: filter?
				'flags'    => 'gated'
			],
			'https://polyfill.io/v3/polyfill.min.js'
			);
		wp_register_script( self::ASSETS_HANDLE, $polyfill_url, [], null, true );
		wp_script_add_data( self::ASSETS_HANDLE, 'crossorigin', 'anonymous' );
	}

	/** {@inheritDoc} */
	public function get_external_domain(): string {
		return 'https://polyfill.io';
	}
	

}
