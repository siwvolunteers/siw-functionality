<?php declare(strict_types=1);

namespace SIW\WooCommerce;

/**
 * Vertalingen voor WooCommerce
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Translations {

	/** Init */
	public static function init() {
		$self = new self();
		
		add_filter( 'woocommerce_register_post_type_product', [ $self, 'set_product_labels'] );
		add_filter( 'woocommerce_register_post_type_shop_order', [ $self, 'set_shop_order_labels'] );
		add_filter( 'woocommerce_taxonomy_args_product_cat', [ $self, 'set_product_category_labels'] );

		add_filter( 'manage_edit-product_columns', [ $self, 'set_product_column_labels'] );

		//Generiek filter, zo min mogelijk gebruiken
		add_filter( 'gettext_woocommerce', [ $self, 'override_translations'], 10, 3 );

		//Losse teksten
		add_filter( 'woocommerce_order_button_text', fn() : string => __( 'Aanmelden', 'siw' ) );
		add_filter( 'woocommerce_product_single_add_to_cart_text', fn() : string => __( 'Aanmelden', 'siw' ) );
		add_filter( 'woocommerce_out_of_stock_message', fn() : string => __( 'Dit project is helaas niet meer beschikbaar', 'siw' ) );
		add_filter( 'woocommerce_sale_flash', fn() : string => '<span class="onsale">' . esc_html__( 'Korting', 'siw' ) . '</span>' );
	}

	/** Zet labels voor producten (projecten) */
	public function set_product_labels( array $args ) : array {
		$args['labels'] = [
			'name'                  => __( 'Projecten', 'siw' ),
			'singular_name'         => __( 'Project', 'siw' ),
			'all_items'             => __( 'Alle projecten', 'siw' ),
			'menu_name'             => _x( 'Projecten', 'Admin menu name', 'siw' ),
			'add_new'               => __( 'Voeg nieuwe toe', 'siw' ),
			'add_new_item'          => __( 'Voeg nieuw project toe', 'siw' ),
			'edit'                  => __( 'Bewerk', 'siw' ),
			'edit_item'             => __( 'Bewerk project', 'siw' ),
			'new_item'              => __( 'Nieuw project', 'siw' ),
			'view_item'             => __( 'Bekijk project', 'siw' ),
			'view_items'            => __( 'Bekijk projecten', 'siw' ),
			'search_items'          => __( 'Zoek projecten', 'siw' ),
			'not_found'             => __( 'Geen projecten gevonden', 'siw' ),
			'not_found_in_trash'    => __( 'Geen projecten gevonden in de prullenbak', 'siw' ),
			'featured_image'        => __( 'Projectafbeelding', 'siw' ),
			'set_featured_image'    => __( 'Zet projectafbeelding', 'siw' ),
			'remove_featured_image' => __( 'Verwijder projectafbeelding', 'siw' ),
			'use_featured_image'    => __( 'Gebruik als projectafbeelding', 'siw' ),
			'filter_items_list'     => __( 'Filter projecten', 'siw' ),
		];
		return $args;
	}

	/** Zet labels voor orders (aanmeldingen) */
	public function set_shop_order_labels( array $args ) : array {
		$args['labels'] = [
			'name'                  => __( 'Aanmeldingen', 'siw' ),
			'singular_name'         => _x( 'Aanmelding', 'shop_order post type singular name', 'siw' ),
			'add_new'               => __( 'Voeg aanmelding toe', 'siw' ),
			'add_new_item'          => __( 'Voeg nieuwe aanmelding toe', 'siw' ),
			'edit'                  => __( 'Bewerk', 'siw' ),
			'edit_item'             => __( 'Bewerk aanmelding', 'siw' ),
			'new_item'              => __( 'Nieuwe aanmelding', 'siw' ),
			'view_item'             => __( 'Bekijk aanmelding', 'siw' ),
			'search_items'          => __( 'Zoek aanmeldingen', 'siw' ),
			'not_found'             => __( 'Geen aanmeldingen gevonden', 'siw' ),
			'not_found_in_trash'    => __( 'Geen aanmeldingen gevonden in de prullenbak', 'siw' ),
			'menu_name'             => _x( 'Aanmeldingen', 'Admin menu name', 'siw' ),
			'filter_items_list'     => __( 'Filter aanmeldingen', 'siw' ),
		];
		return $args;
	}

	/** Labels voor product category (continenten) */
	public function set_product_category_labels( array $args ) : array {
		$args['label'] = __( 'Continenten', 'siw' );
		$args['labels'] = [
			'name'              => __( 'Continenten', 'siw' ),
			'singular_name'     => __( 'Continent', 'siw' ),
			'menu_name'         => _x( 'Continenten', 'Admin menu name', 'siw' ),
			'search_items'      => __( 'Zoek continenten', 'siw' ),
			'all_items'         => __( 'Alle continenten', 'siw' ),
			'edit_item'         => __( 'Bewerk continent', 'siw' ),
			'update_item'       => __( 'Werk continent bij', 'siw' ),
			'add_new_item'      => __( 'Voeg nieuw continent toe', 'siw' ),
			'new_item_name'     => __( 'New category name', 'siw' ),
		];
		return $args;
	}

	/** Labels van admin columns */
	public function set_product_column_labels( array $columns ) : array {
		$columns['sku'] = __( 'Projectcode', 'woocommerce' );
		$columns['product_cat']  = __( 'Continent', 'woocommerce' );
		return $columns;
	}

	/** Overschrijf vertalingen via gettext */
	public function override_translations( string $translation, string $text ) : string {
		switch ( $text ) {
			case 'Product':
				$translation = __( 'Project', 'siw' );
				break;
			case 'Proceed to checkout':
				$translation = __( 'Door naar aanmelden', 'siw' );
				break;
			case 'Your order':
				$translation = __( 'Je aanmelding', 'siw' );
				break;
		}
		return $translation;
	}
}
