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
		add_filter( 'woocommerce_status_log_items_per_page', fn(): int => self::LOG_ITEMS_PER_PAGE );
		add_filter( 'woocommerce_logger_days_to_retain_logs', fn(): int => self::DAYS_TO_RETAIN_LOG );
	}
}
