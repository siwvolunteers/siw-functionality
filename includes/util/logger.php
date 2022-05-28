<?php declare(strict_types=1);

namespace SIW\Util;

/**
 * Logger (wrapper om WC_Logger)
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Logger {

	// Constantes voor levels
	const EMERGENCY = 'emergency';
	const ALERT     = 'alert';
	const CRITICAL  = 'critical';
	const ERROR     = 'error';
	const WARNING   = 'warning';
	const NOTICE    = 'notice';
	const INFO      = 'info';
	const DEBUG     = 'debug';

	/** Schrijf naar log */
	protected static function log( string $message, string $level ) {

		$backtrace = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 0 ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
		$file = $backtrace[1]['file'];
		$line = $backtrace[1]['line'];

		$backtrace = array_slice( $backtrace, 2 );
		self::log_to_woocommerce( $message, $level, $backtrace );
		self::log_to_sentry( $message, $level, $backtrace, $file, $line );
	}

	/** Logt naar WooCommerce */
	protected static function log_to_woocommerce( string $message, string $level, array $backtrace ): void {
		if ( ! function_exists( '\wc_get_logger' ) ) {
			return;
		}
		$severity = match ( $level ) {
			self::EMERGENCY => \WC_Log_Levels::EMERGENCY,
			self::ALERT     => \WC_Log_Levels::ALERT,
			self::CRITICAL  => \WC_Log_Levels::CRITICAL,
			self::ERROR     => \WC_Log_Levels::ERROR,
			self::WARNING   => \WC_Log_Levels::WARNING,
			self::NOTICE    => \WC_Log_Levels::NOTICE,
			self::INFO      => \WC_Log_Levels::INFO,
			self::DEBUG     => \WC_Log_Levels::DEBUG,
		};

		$logger = \wc_get_logger();
		$logger->log( $severity, $message, [ 'source' => $backtrace[0]['class'] ?? 'SIW' ] );

	}

	/** Logt naar Sentry */
	protected static function log_to_sentry( string $message, string $level, array $backtrace, string $file, int $line ): void {
		if ( ! function_exists( 'wp_sentry_safe' ) ) {
			return;
		}

		$severity = match ( $level ) {
			self::EMERGENCY => \Sentry\Severity::fatal(),
			self::ALERT,
			self::CRITICAL,
			self::ERROR     => \Sentry\Severity::error(),
			self::WARNING   => \Sentry\Severity::warning(),
			self::NOTICE,
			self::INFO      => \Sentry\Severity::info(),
			self::DEBUG     => \Sentry\Severity::debug()
		};

		wp_sentry_safe(
			function ( \Sentry\State\HubInterface $client ) use ( $message, $severity, $backtrace, $file, $line ) {
				$stacktrace = $client->getClient()?->getStacktraceBuilder()->buildFromBacktrace( $backtrace, $file, $line );
				$client->captureMessage(
					$message,
					$severity,
					\Sentry\EventHint::fromArray( [ 'stacktrace' => $stacktrace ] )
				);
			}
		);
	}

	/** Schrijf naar log met niveau debug */
	public static function debug( string $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			self::log( $message, self::DEBUG );
		}
	}

	/** Schrijf naar log met niveau info */
	public static function info( string $message ) {
		self::log( $message, self::INFO );
	}

	/** Schrijf naar log met niveau notice */
	public static function notice( string $message ) {
		self::log( $message, self::NOTICE );
	}

	/** Schrijf naar log met niveau warning */
	public static function warning( string $message ) {
		self::log( $message, self::WARNING );
	}

	/** Schrijf naar log met niveau error */
	public static function error( string $message ) {
		self::log( $message, self::ERROR );
	}

	/** Schrijf naar log met niveau critical */
	public static function critical( string $message ) {
		self::log( $message, self::CRITICAL );
	}

	/** Schrijf naar log met niveau alert */
	public static function alert( string $message ) {
		self::log( $message, self::ALERT );
	}

	/** Schrijf naar log met niveau emergency */
	public static function emergency( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::EMERGENCY, $source );
	}
}
