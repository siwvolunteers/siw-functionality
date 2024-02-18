<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	public function get_classes(): array {
		return [
			Admin\Order::class,

			Checkout\Address_Fields::class,
			Checkout\Checkout::class,
			Checkout\Fields::class,
			Checkout\Newsletter::class,
			Checkout\Validation::class,
			Checkout\Discount\Student::class,
			Checkout\Discount\Bulk::class,

			Email\Emails::class,

			Order\Admin\Order_Actions::class,
			Order\Status_Transitions::class,

			Product\Admin\Approval::class,
			Product\Admin\Bulk_Actions::class,
			Product\Admin\Product::class,
			Product\Admin\Tabs::class,
			Product\Archive\Header::class,
			Product\Archive\Loop::class,
			Product\Archive\Ordering::class,
			Product\Product_Type::class,
			Product\Query::class,
			Product\SEO::class,
			Product\Shortcode::class,
			Product\Single\Product::class,
			Product\Single\Tabs::class,

			Log::class,
			Templates::class,
			Translations::class,
		];
	}
}
