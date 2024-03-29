<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Translations extends Base {

	#[Add_Filter( 'woocommerce_order_button_text' )]
	public function set_order_button_text(): string {
		return __( 'Aanmelden', 'siw' );
	}

	#[Add_Filter( 'woocommerce_out_of_stock_message' )]
	public function set_out_of_stock_message(): string {
		return __( 'Dit project is helaas niet meer beschikbaar', 'siw' );
	}

	#[Add_Filter( 'woocommerce_sale_flash' )]
	public function set_sale_flash(): string {
		return '<span class="onsale">' . esc_html__( 'Korting', 'siw' ) . '</span>';
	}

	#[Add_Filter( 'woocommerce_register_post_type_product' )]
	public function set_product_labels( array $args ): array {
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

	#[Add_Filter( 'woocommerce_register_post_type_shop_order' )]
	public function set_shop_order_labels( array $args ): array {
		$args['labels'] = [
			'name'               => __( 'Aanmeldingen', 'siw' ),
			'singular_name'      => _x( 'Aanmelding', 'shop_order post type singular name', 'siw' ),
			'add_new'            => __( 'Voeg aanmelding toe', 'siw' ),
			'add_new_item'       => __( 'Voeg nieuwe aanmelding toe', 'siw' ),
			'edit'               => __( 'Bewerk', 'siw' ),
			'edit_item'          => __( 'Bewerk aanmelding', 'siw' ),
			'new_item'           => __( 'Nieuwe aanmelding', 'siw' ),
			'view_item'          => __( 'Bekijk aanmelding', 'siw' ),
			'search_items'       => __( 'Zoek aanmeldingen', 'siw' ),
			'not_found'          => __( 'Geen aanmeldingen gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Geen aanmeldingen gevonden in de prullenbak', 'siw' ),
			'menu_name'          => _x( 'Aanmeldingen', 'Admin menu name', 'siw' ),
			'filter_items_list'  => __( 'Filter aanmeldingen', 'siw' ),
		];
		return $args;
	}

	#[Add_Filter( 'woocommerce_taxonomy_args_product_cat' )]
	public function set_product_category_labels( array $args ): array {
		$args['label'] = __( 'Projecttypes', 'siw' );
		$args['labels'] = [
			'name'          => __( 'Projecttypes', 'siw' ),
			'singular_name' => __( 'Projecttype', 'siw' ),
			'menu_name'     => _x( 'Projecttypes', 'Admin menu name', 'siw' ),
			'search_items'  => __( 'Zoek projecttypes', 'siw' ),
			'all_items'     => __( 'Alle projecttypes', 'siw' ),
			'edit_item'     => __( 'Bewerk projecttype', 'siw' ),
			'update_item'   => __( 'Werk projecttype bij', 'siw' ),
			'add_new_item'  => __( 'Voeg nieuw projecttype toe', 'siw' ),
			'new_item_name' => __( 'Nieuw projecttype', 'siw' ),
		];
		return $args;
	}


	#[Add_Filter( 'manage_edit-product_columns' )]
	/** Labels van admin columns */
	public function set_product_column_labels( array $columns ): array {
		$columns['sku'] = __( 'Projectcode', 'siw' );
		$columns['product_cat']  = __( 'Projecttype', 'siw' );
		return $columns;
	}

	#[Add_Filter( 'gettext_woocommerce' )]
	public function override_translations( string $translation, string $text ): string {

		$translation = match ( $text ) {
			'Product'                => __( 'Project', 'siw' ),
			'Proceed to checkout'    => __( 'Door naar aanmelden', 'siw' ),
			'Your order'             => __( 'Je aanmelding', 'siw' ),
			'Billing &amp; Shipping' => __( 'Je gegevens', 'siw' ),
			default                  => $translation,
		};

		return $translation;
	}
}
