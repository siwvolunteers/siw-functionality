<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Data\Plato\Project_Free_Places as Plato_Project_Free_Places;

/**
 * Functies voor importeren van de vrije plaatsen van een project
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Free_Places {

	/** Meta om aan te geven dat een project vol is */
	const META_KEY = 'project_is_full';

	/** Vrije plaatsen van een project */
	protected Plato_Project_Free_Places $plato_project_free_places;

	/** Init */
	public function __construct( Plato_Project_Free_Places $plato_project_free_places ) {
		$this->plato_project_free_places = $plato_project_free_places;
	}

	/** Verwerk fpl  */
	public function process() {

		$product = $this->find_project( $this->plato_project_free_places->get_project_id() );
		if ( null == $product ) {
			return;
		}

		$is_full = $this->is_full(
			$this->plato_project_free_places->get_free_m(),
			$this->plato_project_free_places->get_free_f(),
			$this->plato_project_free_places->get_no_more_from(),
		);

		if ( $is_full !== (bool) $product->get_meta( self::META_KEY ) ) {
			$product->update_meta_data( self::META_KEY, $is_full );
			$product->save();
		}
	}

	/** Bepaalt of project vol is */
	protected function is_full( int $free_m, int $free_f, string $no_more_from ) : bool {
		return in_array( 'NLD', wp_parse_slug_list( $no_more_from ) ) || ( ( $free_m + $free_f ) <= 0 );
	}

	/** Zoek project op basis van project_id TODO: util-functie van maken? */
	protected function find_project( string $project_id ) : ?\WC_Product {
			$args = [
			'project_id' => $project_id,
			'return'     => 'objects',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );
	
		return ! empty( $products ) ? reset( $products ) : null;
	}
}
