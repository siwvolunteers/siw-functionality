<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen aan Groepsproject
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product extends Base {

	#[Add_Filter( 'woocommerce_single_product_image_thumbnail_html' )]
	public function remove_link_on_thumbnails( string $html ): string {
		return strip_tags( $html, '<img>' );
	}

	#[Add_Filter( 'woocommerce_display_product_attributes' )]
	public function display_product_attributes( array $attributes, WC_Product_Project $product ): array {
		$order = [
			Product_Attribute::PROJECT_NAME,
			Product_Attribute::PROJECT_CODE,
			Taxonomy_Attribute::CONTINENT,
			Taxonomy_Attribute::COUNTRY,
			Taxonomy_Attribute::WORK_TYPE,
			Product_Attribute::START_DATE,
			Product_Attribute::END_DATE,
			Product_Attribute::NUMBER_OF_VOLUNTEERS,
			Product_Attribute::AGE_RANGE,
			Product_Attribute::PARTICIPATION_FEE,
			Taxonomy_Attribute::LANGUAGE,
			Taxonomy_Attribute::TARGET_AUDIENCE,
			Taxonomy_Attribute::SDG,
		];

		$order = array_map(
			fn( \BackedEnum $attribute ): string => "attribute_{$attribute->value}",
			$order
		);

		uksort(
			$attributes,
			function ( $key1, $key2 ) use ( $order ) {
				return ( array_search( $key1, $order, true ) <=> array_search( $key2, $order, true ) );
			}
		);

		// Local fee verbergen voor nederlandse projecten
		if ( $product->is_dutch_project() ) {
			unset( $attributes['attribute_lokale-bijdrage'] );
		}

		return $attributes;
	}

	#[Add_Action( 'woocommerce_single_product_summary', 9 )]
	public function hide_single_price() {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	}

	#[Add_Action( 'woocommerce_single_product_summary', 20 )]
	public function show_project_summary() {
		//TODO: mustache template gebruiken en leuker maken met landenvlag en SDG icons */

		global $post;
		$product = \siw_get_product( $post );
		if ( null === $product ) {
			return;
		}

		$summary = [
			__( 'Land', 'siw' )                          => $product->get_country()->label(),
			__( 'Soort werk', 'siw' )                    => $product->get_attribute( Taxonomy_Attribute::WORK_TYPE->value ),
			__( 'Projectduur', 'siw' )                   => siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false ),
			__( 'Sustainable Development Goals', 'siw' ) => $product->get_attribute( Taxonomy_Attribute::SDG->value ),
			__( 'Aantal deelnemers', 'siw' )             => $product->get_attribute( Product_Attribute::NUMBER_OF_VOLUNTEERS->value ),
		];

		echo '<p>';
		esc_html_e( 'In het kort:', 'siw' );
		echo '</p>';
		echo '<dl>';
		foreach ( $summary as $label => $value ) {
			if ( ! empty( $value ) ) {
				printf(
					'<dt>%s</dt><dd>%s</dd>',
					esc_html( $label ),
					esc_html( $value )
				);
			}
		}
		echo '</dl>';
		echo '<p>';
		esc_html_e( 'Lees snel verder voor meer informatie over de werkzaamheden, de accommodatie, de projectlocatie en de kosten.', 'siw' );
		echo SPACE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html_e( 'Heb je een vraag over dit project?', 'siw' );
		echo SPACE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html_e( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' );
		echo '</p>';
	}

	#[Add_Action( 'woocommerce_before_single_product_summary' )]
	public function show_featured_badge() {
		global $product;
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}

	#[Add_Action( 'woocommerce_project_add_to_cart', 30 )]
	public function project_add_to_cart() {
		woocommerce_simple_add_to_cart();
	}
}
