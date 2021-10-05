<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\WooCommerce\Taxonomy_Attribute;
use SIW\WooCommerce\WC_Product_Project;

/**
 * TODO: single title en description
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class SEO {

	/** Init */
	public static function init() {
		$self = new self();

		//Single product
		add_filter( 'the_seo_framework_post_meta', [ $self, 'set_seo_noindex' ], 10, 2 );
		add_filter( 'the_seo_framework_title_from_generation', [ $self, 'set_single_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_description', [ $self, 'set_single_seo_description'], 10, 2 );

		//Archive
		add_filter( 'the_seo_framework_the_archive_title', [ $self, 'set_archive_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_archive_seo_description' ], 10, 2 );
	}

	/** Zet SEO noindex als project niet zichtbaar is */
	public function set_seo_noindex( array $meta, int $post_id ) : array {
		$product = siw_get_product( $post_id );
		if ( is_a( $product, WC_Product_Project::class ) ) {
			$meta['_genesis_noindex'] = intval( ! $product->is_purchasable() );
		}
		return $meta;
	}

	/** Zet SEO-titel van project */
	public function set_single_seo_title( string $title, ?array $args ): string {

		if ( null != $args ) {
			return $title;
		}

		global $post;
		
		$product = siw_get_product( $post );
		if ( null == $product ) {
			return $title;
		}

		return sprintf(
			'Vrijwilligersproject %s | %s',
			$product->get_country()->get_name(),
			ucfirst( $product->get_work_types()[0]->get_name() ) );
	}

	/** Zet SEO-beschrijving van project */
	public function set_single_seo_description( string $description, ?array $args ): string {

		if ( null != $args ) {
			return $description;
		}

		global $post;
		$product = siw_get_product( $post );
		if ( null == $product ) {
			return $description;
		}
	
		//TODO: genereren

		return $description;
	}

	/** Past de SEO titel aan */
	public function set_archive_seo_title( string $title, $term ) : string {

		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case Taxonomy_Attribute::CONTINENT():
			case Taxonomy_Attribute::COUNTRY():
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::TARGET_AUDIENCE():
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::LANGUAGE():
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case Taxonomy_Attribute::WORK_TYPE():
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::SDG():
				$title = sprintf( __( 'Groepsprojecten met werk gericht op het SDG %s', 'siw' ), strtolower( $term->name ) );
				break;
			case Taxonomy_Attribute::MONTH():
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/** Past SEO-beschrijving aan */
	public function set_archive_seo_description( string $description, $term ): string {
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return $description;
		}
		
		switch ( $term->taxonomy ) {
			case Taxonomy_Attribute::CONTINENT():
			case Taxonomy_Attribute::COUNTRY():
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::WORK_TYPE():
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case Taxonomy_Attribute::SDG():
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op het Sustainable Development Goal %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $description;
	}
}
