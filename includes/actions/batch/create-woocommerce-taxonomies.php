<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\Util\Logger;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Bijwerken WooCommerce terms
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Create_WooCommerce_Taxonomies implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'create_woocommerce_taxonomies';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Aanmaken WooCommerce taxonomieën', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		return array_map( fn( \BackedEnum $tax_enum ) => $tax_enum->value, Taxonomy_Attribute::cases() );
	}

	/** {@inheritDoc} */
	public function process( $taxonomy_slug ) {

		if ( 'product_cat' === $taxonomy_slug ) {
			return;
		}

		$wc_attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy_slug );

		if ( 0 !== $wc_attribute_taxonomy_id ) {
			return;
		}

		$taxonomy_attribute = Taxonomy_Attribute::tryFrom( $taxonomy_slug );

		$wc_attribute_taxonomy_id = wc_create_attribute(
			[
				'name'         => $taxonomy_attribute->label(),
				'slug'         => $taxonomy_attribute->value,
				'type'         => 'select',
				'order_by'     => $this->determine_orderby( $taxonomy_attribute ),
				'has_archives' => true,
			]
		);
		if ( is_wp_error( $wc_attribute_taxonomy_id ) ) {
			Logger::error(
				sprintf(
					'Aanmaken WC taxonomy %s mislukt: %s',
					$taxonomy_slug,
					$wc_attribute_taxonomy_id->get_error_message()
				),
				static::class
			);
		}
	}

	protected function determine_orderby( Taxonomy_Attribute $attribute ): string {
		return match ( $attribute->value ) {
			Taxonomy_Attribute::SDG->value => 'name_num',
			Taxonomy_Attribute::MONTH->value => 'menu_order',
			default => 'name'
		};
	}
}
