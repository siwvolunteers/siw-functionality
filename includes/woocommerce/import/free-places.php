<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Facades\WooCommerce;
use SIW\Plato\Database\Free_Places\Row;

class Free_Places {

	public function __construct( protected Row $plato_project_free_places ) {}

	public function process() {
		$product = WooCommerce::get_product_by_project_id( $this->plato_project_free_places->project_id );
		if ( null === $product ) {
			return;
		}

		$is_full = $this->is_full(
			$this->plato_project_free_places->free_m,
			$this->plato_project_free_places->free_f,
			$this->plato_project_free_places->no_more_from,
		);

		if ( $is_full !== $product->is_full() ) {
			$product->set_full( $is_full );
			$product->save();
		}
	}

	protected function is_full( int $free_m, int $free_f, string $no_more_from ): bool {
		return in_array( 'NLD', wp_parse_slug_list( $no_more_from ), true ) || ( ( $free_m + $free_f ) <= 0 );
	}
}
