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
	public function get_id() : string {
		return 'woocommerce';
	}

	/** {@inheritDoc} */
	public function get_classes() : array {
		return [
			Admin\Coupon::class,
			Admin\Order::class,
			Admin\Product_Tabs::class,
			Admin\Product::class,
			Admin\Stockphoto_Page::class,

			Checkout\Discount::class,
			Checkout\Fields::class,
			Checkout\Form::class,
			Checkout\Newsletter::class,
			Checkout\Terms::class,
			Checkout\Validation::class,

			Email\Customer_On_Hold_Order::class,
			Email\Customer_Processing_Order::class,
			Email\Emails::class,
			Email\New_Order::class,

			Export\Order::class,
			Frontend\Product::class,
			Frontend\Product_Tabs::class,

			Product\Archive\Header::class,
			Product\Archive\Ordering::class,
			Product_Type::class,
			Translations::class,
		];
	}

	/** {@inheritDoc} */
	public function load( string $class ) {
		$class::init();
	}
}
