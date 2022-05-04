<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Helpers\Template;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * SEO aanpassingen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class SEO {

	/** Init */
	public static function init() {
		$self = new self();

		// Archive
		add_filter( 'the_seo_framework_generated_archive_title', [ $self, 'set_archive_seo_title' ], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_archive_seo_description' ], 10, 2 );

		// Single product
		add_filter( 'the_seo_framework_post_meta', [ $self, 'set_single_seo_noindex' ], 10, 2 );
		add_filter( 'the_seo_framework_title_from_generation', [ $self, 'set_single_seo_title' ], 10, 2 );
		add_filter( 'the_seo_framework_generated_description', [ $self, 'set_single_seo_description' ], 10, 2 );
	}

	/** Past de SEO titel aan */
	public function set_archive_seo_title( string $title, $term ): string {

		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case Taxonomy_Attribute::CONTINENT()->value:
			case Taxonomy_Attribute::COUNTRY()->value:
				// translators: %s is een continent of land
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::TARGET_AUDIENCE()->value:
				// translators: %s is doelgroep
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::LANGUAGE()->value:
				// translators: %s is de taal
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::WORK_TYPE()->value:
				// translators: %s is het soort werk
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::SDG()->value:
				// translators: %s is de SDG
				$title = sprintf( __( 'Groepsprojecten met werk gericht op het SDG %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::MONTH()->value:
				// translators: %s is een maand
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/**
	 * Past SEO-beschrijving aan */
	public function set_archive_seo_description( string $description, $term ): string {
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $description;
		}

		switch ( $term->taxonomy ) {
			case Taxonomy_Attribute::CONTINENT()->value:
			case Taxonomy_Attribute::COUNTRY()->value:
				$description =
				// translators: %s is een continent of land
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::WORK_TYPE()->value:
				$description =
				// translators: %s is een soort werk
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::SDG()->value:
				$description =
				// translators: %s is een SDG
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op het Sustainable Development Goal %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $description;
	}

	/** Zet SEO noindex als project niet zichtbaar is */
	public function set_single_seo_noindex( array $meta, int $post_id ): array {
		if ( 'product' !== get_post_type( $post_id ) ) {
			return $meta;
		}

		$product = siw_get_product( $post_id );
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return $meta;
		}

		$meta['_genesis_noindex'] = intval( ! $product->is_visible() );

		return $meta;
	}

	/** Zet SEO-titel van project */
	public function set_single_seo_title( string $title, ?array $args ): string {

		if ( ! is_a( get_queried_object(), \WP_Post::class ) && ! isset( $args['id'] ) ) {
			return $title;
		}

		$post_id = $args['id'] ?? get_queried_object_id();

		$product = siw_get_product( $post_id );
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return $title;
		}

		return sprintf(
			'Vrijwilligersproject %s | %s',
			$product->get_attribute( Taxonomy_Attribute::COUNTRY()->value ),
			$product->get_attribute( Taxonomy_Attribute::WORK_TYPE()->value )
		);
	}

	/** Zet SEO-beschrijving van project */
	public function set_single_seo_description( string $description, ?array $args ): string {

		if ( ! is_a( get_queried_object(), \WP_Post::class ) && ! isset( $args['id'] ) ) {
			return $description;
		}

		$post_id = $args['id'] ?? get_queried_object_id();

		$product = siw_get_product( $post_id );
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
			'country'   => $product->get_attribute( Taxonomy_Attribute::COUNTRY()->value ),
			'work_type' => $product->get_attribute( Taxonomy_Attribute::WORK_TYPE()->value ),
		];
		return Template::create()->set_template( $template )->set_context( $context )->parse_template();
	}
}
