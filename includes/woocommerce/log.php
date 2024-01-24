<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Log extends Base {

	#[Add_Filter( 'woocommerce_status_log_items_per_page' )]
	private const LOG_ITEMS_PER_PAGE = 25;

	#[Add_Filter( 'woocommerce_logger_days_to_retain_logs' )]
	private const DAYS_TO_RETAIN_LOG = 7;
}
