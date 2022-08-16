<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Abstracts\Class_Loader as Class_Loader_Abstract;

/**
 * Loader voor WooCommerce
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Loader extends Class_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Admin\Order::class,
			Admin\Product_Tabs::class,
			Admin\Product::class,
			Admin\Stockphoto_Page::class,

			Checkout\Address_Fields::class,
			Checkout\Discount::class,
			Checkout\Fields::class,
			Checkout\Form::class,
			Checkout\Newsletter::class,
			Checkout\Validation::class,

			Email\Customer_On_Hold_Order::class,
			Email\Customer_Processing_Order::class,
			Email\Emails::class,
			Email\New_Order::class,

			Frontend\Archive::class,
			Frontend\Product::class,
			Frontend\Product_Tabs::class,

			Order\Admin\Order_Actions::class,
			Order\Status_Transitions::class,

			Product\Admin\Approval::class,
			Product\Admin\Bulk_Actions::class,
			Product\Archive\Header::class,
			Product\Archive\Ordering::class,
			Product\Product_Type::class,
			Product\Query::class,
			Product\SEO::class,
			Product\Shortcode::class,

			Log::class,
			Templates::class,
			Translations::class,
		];
	}

	/** {@inheritDoc} */
	public function load( string $class ) {
		$class::init();
	}
}
