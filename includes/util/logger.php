<?php declare(strict_types=1);

namespace SIW\Util;

use SIW\Facades\WooCommerce;

class Logger {

	protected static function log( string $message, string $level, string $source, array $context = [] ) {
		if ( ! \WC_Log_Levels::is_valid_level( $level ) ) {
			$level = \WC_Log_Levels::INFO;
		}

		$context['source'] = str_replace( '\\', '-', $source );
		WooCommerce::get_logger()?->log( $level, $message, $context );
	}

	public static function debug( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::DEBUG, $source, $context );
	}

	public static function info( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::INFO, $source, $context );
	}

	public static function notice( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::NOTICE, $source, $context );
	}

	public static function warning( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::WARNING, $source, $context );
	}

	public static function error( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::ERROR, $source, $context );
	}

	public static function critical( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::CRITICAL, $source, $context );
	}

	public static function alert( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::ALERT, $source, $context );
	}

	public static function emergency( string $message, string $source, array $context = [] ) {
		self::log( $message, \WC_Log_Levels::EMERGENCY, $source, $context );
	}
}
