<?php declare(strict_types=1);

namespace SIW\WooCommerce;

/**
 * Logging
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Log {

	/** Aantal log items per pagina */
	const LOG_ITEMS_PER_PAGE = 25;

	/** Aantal dagen dat log bewaard wordt */
	const DAYS_TO_RETAIN_LOG = 7;

	/** Init */
	public static function init() {
		$self = new self();
		define( 'WC_LOG_HANDLER', 'WC_Log_Handler_DB' );
		add_filter( 'woocommerce_register_log_handlers', [ $self, 'register_log_handlers' ], PHP_INT_MAX );
		add_filter( 'woocommerce_status_log_items_per_page', fn(): int => self::LOG_ITEMS_PER_PAGE );
		add_filter( 'woocommerce_logger_days_to_retain_logs', fn(): int => self::DAYS_TO_RETAIN_LOG );
	}

	/**
	 * Registreert log handlers
	 * 
	 * - Database
	 * - E-mail (voor hoge prioriteit)
	 */
	public function register_log_handlers(): array {
		$log_handler_db = new \WC_Log_Handler_DB;
		$log_handler_email = new \WC_Log_Handler_Email;
		$log_handler_email->set_threshold( 'alert' );

		$handlers = [
			$log_handler_db,
			$log_handler_email,
		];

		return $handlers;
	}
}