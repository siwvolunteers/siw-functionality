<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor WP Sentry Integration
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class WP_Sentry_Integration {

	/**
	 * PHP-DSN
	 *
	 * @var string
	 */
	const PHP_DSN = 'https://d66e53bd9d3e41199ff984851c98706b@sentry.io/1264830';

	/**
	 * JS-DSN
	 *
	 * @var string
	 */
	const JS_DSN = 'https://e8240c08387042d583692b6415c700e3@sentry.io/1264820';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		$self->define_constants();
		add_filter( 'rocket_exclude_js', [ $self, 'exclude_js_from_combine' ] );
	}

	/**
	 * Definieer constantes voor WP Sentry
	 */
	public function define_constants() {
		$constants = [
			'WP_SENTRY_VERSION'     => SIW_PLUGIN_VERSION,
			'WP_SENTRY_ENV'         => SIW_ENVIRONMENT,
			'WP_SENTRY_ERROR_TYPES' => E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED,
			'WP_SENTRY_PUBLIC_DSN'  => self::JS_DSN,
			'WP_SENTRY_DSN'         => self::PHP_DSN,
			'WP_SENTRY_DEFAULT_PII' => true,
		];

		foreach ( $constants as $constant => $value ) {
			if ( ! defined( $constant ) && ! empty( $value ) ) {
				define( $constant, $value );
			}
		}
	}

	/**
	 * JS-bestanden uitsluiten van minification/concatenation
	 *
	 * @param array $excluded_files
	 * @return array
	 */
	public function exclude_js_from_combine( array $excluded_files ) {
		$excluded_files[] = '/wp-content/plugins/wp-sentry-integration/public/(.*).js';
		return $excluded_files;
	}

}
