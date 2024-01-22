<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Bijwerken WooCommerce terms
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Update_WooCommerce_Terms implements Batch_Action_Interface {

	/** Term meta voor aantal zichtbare posts */
	private const POST_COUNT_TERM_META = 'post_count';

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_woocommerce_terms';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return 'Bijwerken WooCommerce terms';
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function select_data(): array {

		$data = get_terms(
			[
				'taxonomy'   => array_map( fn( \BackedEnum $tax_enum ) => $tax_enum->value, Taxonomy_Attribute::cases() ),
				'fields'     => 'tt_ids',
				'hide_empty' => false,
			]
		);

		if ( is_wp_error( $data ) ) {
			return [];
		}
		return $data;
	}

	/** {@inheritDoc} */
	public function process( $term_taxonomy_id ) {

		if ( ! is_int( $term_taxonomy_id ) ) {
			return;
		}

		$term = get_term_by( 'term_taxonomy_id', $term_taxonomy_id );
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return;
		}

		// Taxonomy subquery
		$tax_query = [
			[
				'taxonomy' => $term->taxonomy,
				'field'    => 'slug',
				'terms'    => $term->slug,
			],
		];

		$visible_posts = siw_get_product_ids(
			[
				'tax_query'  => $tax_query,
				'visibility' => 'visible',
			]
		);
		$posts = siw_get_product_ids(
			[
				'tax_query' => $tax_query,
			]
		);

		$count = count( $posts );
		$visible_count = count( $visible_posts );

		// Alleen van maand moeten lege waardes verwijderd worden
		$delete_empty = Taxonomy_Attribute::MONTH->value === $term->taxonomy;

		// Lege terms eventueel weggooien
		if ( $delete_empty && 0 === $count ) {
			wp_delete_term( $term->term_id, $term->taxonomy );
			return false;
		}

		$current_count = intval( get_term_meta( $term->term_id, self::POST_COUNT_TERM_META, true ) );
		if ( $current_count !== $visible_count ) {
			update_term_meta( $term->term_id, self::POST_COUNT_TERM_META, $visible_count );
		}
	}
}
