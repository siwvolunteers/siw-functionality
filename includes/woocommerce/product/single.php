<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Elements\Icon;
use SIW\WooCommerce\Product_Attribute;

/**
 * TODO:
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Single {

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $self, 'remove_link_on_thumbnails'] );

		add_action( 'woocommerce_single_product_summary', [ $self, 'show_project_data' ], 15 );

		add_action( 'woocommerce_single_product_summary', [ $self, 'show_general_summary'], 20 ); //TODO: betere functienaam

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


		//TODO: uitleg
		add_action( 'woocommerce_project_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );

		add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );

	}


	/** Verwijdert link bij productafbeelding */
	public function remove_link_on_thumbnails( string $html ) : string {
		return strip_tags( $html, '<img>' );
	}

	/** Toont het land */
	public function show_project_data() {
		global $post;
		$product = \siw_get_product( $post );
		if ( null == $product ) {
			return;
		}

		//echo wpautop( esc_html( $product->get_country()->get_name() ) );

		foreach ( $product->get_work_types() as $work_type ) {
			printf(
				'%s %s<br>',
				Icon::create()->set_icon_class( $work_type->get_icon_class() )->set_has_background(false)->generate(),
				$work_type->get_name() );
		}

		printf (
			'%s %s <br>',
			Icon::create()->set_icon_class( 'siw-icon-calendar-check')->set_has_background(false)->generate(),
			\siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false )
		);
		printf (
			'%s %s <br>',
			Icon::create()->set_icon_class( 'siw-icon-users')->set_has_background(false)->generate(),
			$product->get_attribute( Product_Attribute::NUMBER_OF_VOLUNTEERS()->value )
		);

		//TODO: taal, sdg
	}

	/** Toont algemene samenvatting TODO: netter/mustache template / samenvoegen met show_project_data*/
	public function show_general_summary() {
		echo '<p>'.
		__( 'Lees snel verder voor meer informatie over het tarief, werk, accommodatie en projectlocatie.', 'siw' ) . SPACE .
		__( 'Heb je een vraag over dit project?', 'siw' ) . SPACE .
		__( 'Laat je gegevens achter bij "Stel een vraag" en we nemen zo snel mogelijk contact met je op.', 'siw' ) . SPACE .
		'</p>';
	}

}
