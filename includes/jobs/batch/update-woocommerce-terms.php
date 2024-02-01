<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Jobs\Scheduled_Job;
use SIW\WooCommerce\Taxonomy_Attribute;

class Update_WooCommerce_Terms extends Scheduled_Job {

	private const ACTION_HOOK = self::class;

	/** Term meta voor aantal zichtbare posts */
	private const POST_COUNT_TERM_META = 'post_count';

	#[\Override]
	public function get_name(): string {
		return 'Bijwerken WooCommerce terms';
	}

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::DAILY;
	}

	#[\Override]
	public function start(): void {

		$data = get_terms(
			[
				'taxonomy'   => array_map( fn( \BackedEnum $tax_enum ) => $tax_enum->value, Taxonomy_Attribute::cases() ),
				'fields'     => 'tt_ids',
				'hide_empty' => false,
			]
		);

		if ( is_wp_error( $data ) ) {
			return;
		}

		$this->enqueue_items( $data, self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function update_term( $term_taxonomy_id ) {

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
