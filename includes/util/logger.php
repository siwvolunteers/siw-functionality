<?php declare(strict_types=1);

namespace SIW\Util;

/**
 * Logger (wrapper om WC_Logger)
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Logger {

	/** Schrijf naar log */
	public static function log( string $message, string $level, string $source = 'siw' ) {
		if ( ! \WC_Log_Levels::is_valid_level( $level ) ) {
			$level = \WC_Log_Levels::INFO;
		}
		if ( ! function_exists( '\wc_get_logger' ) ) {
			return;
		}

		$logger = \wc_get_logger();
		$logger->log( $level, $message, [ 'source'=> $source ] );
	}

	/** Schrijf naar log met niveau debug */
	public static function debug( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::DEBUG, $source );
	}

	/** Schrijf naar log met niveau info */
	public static function info( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::INFO, $source );
	}

	/** Schrijf naar log met niveau notice */
	public static function notice( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::NOTICE, $source );
	}

	/** Schrijf naar log met niveau warning */
	public static function warning( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::WARNING, $source );
	}

	/** Schrijf naar log met niveau error */
	public static function error( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::ERROR, $source );
	}

	/** Schrijf naar log met niveau critical */
	public static function critical( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::CRITICAL, $source );
	}

	/** Schrijf naar log met niveau alert */
	public static function alert( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::ALERT, $source );
	}

	/** Schrijf naar log met niveau emergency */
	public static function emergency( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::EMERGENCY, $source );
	}
}
