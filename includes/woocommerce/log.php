<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Attributes\Filter;
use SIW\Base;

/**
 * Logging
 *
 * @copyright 2021-2023 SIW Internationale Vrijwilligersprojecten
 */
class Log extends Base {

	#[Filter( 'woocommerce_status_log_items_per_page' )]
	private const LOG_ITEMS_PER_PAGE = 25;

	#[Filter( 'woocommerce_logger_days_to_retain_logs' )]
	private const DAYS_TO_RETAIN_LOG = 7;
}
