<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Compatibility\WooCommerce;
use SIW\Facades\WooCommerce as WooCommerce_Facade;
use SIW\Helpers\Template;
use SIW\WooCommerce\Taxonomy_Attribute;

class SEO extends Base {

	#[Add_Filter( 'slim_seo_meta_title' )]
	public function set_term_seo_title( string $title, int $queried_object_id ): string {

		$term = get_queried_object();

		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case Taxonomy_Attribute::CONTINENT->value:
			case Taxonomy_Attribute::COUNTRY->value:
				// translators: %s is een continent of land
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::TARGET_AUDIENCE->value:
				// translators: %s is doelgroep
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::LANGUAGE->value:
				// translators: %s is de taal
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::WORK_TYPE->value:
				// translators: %s is het soort werk
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::SDG->value:
				// translators: %s is de SDG
				$title = sprintf( __( 'Groepsprojecten met werk gericht op het SDG %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::MONTH->value:
				// translators: %s is een maand
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	#[Add_Filter( 'get_term' )]
	/** Zet naam van terms */
	public function set_term_description( \WP_Term $term, string $taxonomy ): \WP_Term {
		switch ( $taxonomy ) {
			case Taxonomy_Attribute::CONTINENT->value:
			case Taxonomy_Attribute::COUNTRY->value:
				$term->description =
				// translators: %s is een continent of land
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::WORK_TYPE->value:
				$term->description =
				// translators: %s is een soort werk
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::SDG->value:
				$term->description =
				// translators: %s is een SDG
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op het Sustainable Development Goal %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $term;
	}

	#[Add_Filter( 'slim_seo_robots_index' )]
	public function set_seo_robots_index( bool $index, int $post_id ): bool {
		if ( WooCommerce::PRODUCT_POST_TYPE !== get_post_type( $post_id ) ) {
			return $index;
		}

		$product = WooCommerce_Facade::get_product( $post_id );
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return $index;
		}

		return $product->is_visible();
	}
	#[Add_Filter( 'slim_seo_meta_title' )]
	public function set_single_seo_title( string $title, int $queried_object_id ): string {

		if ( ! is_a( get_queried_object(), \WP_Post::class ) ) {
			return $title;
		}

		$product = WooCommerce_Facade::get_product( $queried_object_id );
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return $title;
		}

		return sprintf(
			'Vrijwilligersproject %s | %s',
			$product->get_attribute( Taxonomy_Attribute::COUNTRY->value ),
			$product->get_attribute( Taxonomy_Attribute::WORK_TYPE->value )
		);
	}

	#[Add_Filter( 'slim_seo_meta_description_generated' )]
	public function set_single_seo_description( string $description, ?\WP_Post $post ): string {

		$product = WooCommerce_Facade::get_product( $post );
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return $description;
		}

		// TODO: betere beschrijving maken
		$templates = [
			'Op zoek naar een vrijwilligersproject in {{ country }}? Zet je in voor een project gericht op {{ work_type }}.',
			'Op zoek naar een vrijwilligersproject gericht op {{ work_type }}? Zet je in voor een project in {{ country }}.',
		];
		$template = $templates[ array_rand( $templates, 1 ) ];

		$context = [
			'country'   => $product->get_attribute( Taxonomy_Attribute::COUNTRY->value ),
			'work_type' => $product->get_attribute( Taxonomy_Attribute::WORK_TYPE->value ),
		];
		return Template::create()->set_template( $template )->set_context( $context )->parse_template();
	}
}
