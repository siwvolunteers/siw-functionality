<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Archive;

use SIW\Attributes\Filter;
use SIW\Base;

/**
 * Sortering van projecten in frontend
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Ordering extends Base {

	#[Filter( 'woocommerce_default_catalog_orderby_options' )]
	#[Filter( 'woocommerce_catalog_orderby' )]
	public function add_catalog_orderby_options( array $options ): array {
		unset( $options['menu_order'] );
		unset( $options['popularity'] );
		unset( $options['rating'] );
		unset( $options['date'] );
		unset( $options['price'] );
		unset( $options['price-desc'] );
		$options['startdate'] = __( 'Startdatum', 'siw' );
		return $options;
	}

	#[Filter( 'woocommerce_get_catalog_ordering_args' )]
	public function process_catalog_ordering_args( array $args, string $orderby, string $order ): array {
		if ( 'startdate' === $orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = '_start_date';
		}
		return $args;
	}
}
