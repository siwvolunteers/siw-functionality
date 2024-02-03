<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Data\Plato\Project_Free_Places as Plato_Project_Free_Places;
use SIW\Facades\WooCommerce;

/**
 * Functies voor importeren van de vrije plaatsen van een project
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Free_Places {

	/** Init */
	public function __construct( protected Plato_Project_Free_Places $plato_project_free_places ) {}

	/** Verwerk fpl  */
	public function process() {

		$product = WooCommerce::get_product_by_project_id( $this->plato_project_free_places->get_project_id() );
		if ( null === $product ) {
			return;
		}

		$is_full = $this->is_full(
			$this->plato_project_free_places->get_free_m(),
			$this->plato_project_free_places->get_free_f(),
			$this->plato_project_free_places->get_no_more_from(),
		);

		if ( $is_full !== $product->is_full() ) {
			$product->set_full( $is_full );
			$product->save();
		}
	}

	/** Bepaalt of project vol is */
	protected function is_full( int $free_m, int $free_f, string $no_more_from ): bool {
		return in_array( 'NLD', wp_parse_slug_list( $no_more_from ), true ) || ( ( $free_m + $free_f ) <= 0 );
	}
}
