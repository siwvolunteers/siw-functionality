<?php

/**
 * Aanpassingen voor WP Sentry Integration
 * 
 * @package     SIW\Compatibility
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat_WP_Sentry_Integration {

	/**
	 * Init
	 */
	public static function init() {

		$self = new self();
		$self->define_constants();
	}

	/**
	 * Definieer constantes voor WP Sentry
	 */
	public function define_constants() {
		$constants = [
			'WP_SENTRY_VERSION'     => SIW_PLUGIN_VERSION,
			'WP_SENTRY_ENV'         => SIW_ENVIRONMENT,
			'WP_SENTRY_ERROR_TYPES' => E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED,
			'WP_SENTRY_PUBLIC_DSN'  => SIW_Properties::SENTRY_JS_DSN,
			'WP_SENTRY_DSN'         => SIW_Properties::SENTRY_PHP_DSN,
			'WP_SENTRY_DEFAULT_PII' => true,
		];

		foreach ( $constants as $constant => $value ) {
			if ( ! defined( $constant ) && ! empty( $value ) ) {
				define( $constant, $value );
			}
		}
	}
}
