<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Jobs\Update_Job;
use SIW\Util\Logger;
use SIW\WooCommerce\Taxonomy_Attribute;

class Create_WooCommerce_Taxonomies extends Update_Job {

	private const ACTION_HOOK = self::class;

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmaken WooCommerce taxonomieën', 'siw' );
	}

	#[\Override]
	public function start(): void {
		$this->enqueue_items(
			array_map( fn( \BackedEnum $tax_enum ) => $tax_enum->value, Taxonomy_Attribute::cases() ),
			self::ACTION_HOOK,
		);
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function create_taxonomy( string $taxonomy_slug ) {

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
