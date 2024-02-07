<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Facades\WooCommerce;

class Logger {

	protected static function log( string $message, string $level, string $source = 'siw' ) {
		if ( ! \WC_Log_Levels::is_valid_level( $level ) ) {
			$level = \WC_Log_Levels::INFO;
		}

		WooCommerce::get_logger()?->log( $level, $message, [ 'source' => $source ] );
	}

	public static function debug( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::DEBUG, $source );
	}

	public static function info( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::INFO, $source );
	}

	public static function notice( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::NOTICE, $source );
	}

	public static function warning( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::WARNING, $source );
	}

	public static function error( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::ERROR, $source );
	}

	public static function critical( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::CRITICAL, $source );
	}

	public static function alert( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::ALERT, $source );
	}

	public static function emergency( string $message, string $source ) {
		self::log( $message, \WC_Log_Levels::EMERGENCY, $source );
	}
}
