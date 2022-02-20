<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Archive;

/**
 * Sortering van projecten in frontend
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Ordering {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_default_catalog_orderby_options', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_catalog_orderby', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_get_catalog_ordering_args', [ $self, 'process_catalog_ordering_args' ], 10, 3 );
	}

	/** Voegt extra sorteeroptie (startdatum) toe voor archive */
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

	/** Verwerkt extra sorteeroptie voor archive */
	public function process_catalog_ordering_args( array $args, string $orderby, string $order ): array {
		if ( 'startdate' === $orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'start_date';
		}
		return $args;
	}

}