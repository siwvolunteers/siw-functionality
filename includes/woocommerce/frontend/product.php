<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Data\Currency;
use SIW\External\Exchange_Rates;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Taxonomy_Attribute;
use SIW\WooCommerce\WC_Product_Project;
use Spatie\Enum\Enum;

/**
 * Aanpassingen aan Groepsproject
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_is_purchasable', fn( bool $is_purchasable, \WC_Product $product ) => $product->is_visible(), 10, 2 );
		add_filter( 'woocommerce_display_product_attributes', [ $self, 'display_product_attributes'], 10, 2 );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'show_local_fee'] );
		add_action( 'woocommerce_before_single_product_summary', [ $self, 'show_featured_badge' ], 10 );
	}


	/** Past weergave van de attributes aan */
	public function display_product_attributes( array $attributes, WC_Product_Project $product ) : array {
		$order = [
			Product_Attribute::PROJECT_NAME(),
			Product_Attribute::PROJECT_CODE(),
			Taxonomy_Attribute::COUNTRY(),
			Taxonomy_Attribute::WORK_TYPE(),
			Product_Attribute::START_DATE(),
			Product_Attribute::END_DATE(),
			Product_Attribute::NUMBER_OF_VOLUNTEERS(),
			Product_Attribute::AGE_RANGE(),
			Product_Attribute::PARTICIPATION_FEE(),
			Taxonomy_Attribute::LANGUAGE(),
			Taxonomy_Attribute::TARGET_AUDIENCE(),
			Taxonomy_Attribute::SDG(),
		];

		$order = array_map(
			fn( Enum $attribute ) : string => "attribute_{$attribute->value}",
			$order
		);

		uksort( $attributes, function( $key1, $key2 ) use ( $order ) {
			return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
		} );

		//Local fee verbergen voor nederlandse projecten
		if ( 'nederland' === $product->get_country()->get_slug() ) {
			unset( $attributes['attribute_lokale-bijdrage']);
		}

		return $attributes;
	}
	
	/** Toont lokale bijdrage indien van toepassing */
	public function show_local_fee() {
		global $post;

		$product = siw_get_product( $post );
		
		//Local fee niet tonen voor nederlandse projecten
		if ( null == $product || $product->is_dutch_project() ) {
			return;
		}

		$amount = $product->get_participation_fee();
		$currency_code = $product->get_participation_fee_currency();

		if ( empty( $currency_code ) || $amount <= 0 ) {
			return;
		}
		
		$currency = siw_get_currency( $currency_code );
		$symbol = $currency_code;
		if ( is_a( $currency, Currency::class ) ) {
			$symbol = $currency->get_symbol();
			
			if ( get_woocommerce_currency() != $currency->get_iso_code() ) {
				$exchange_rates = new Exchange_Rates();
				$amount_in_euro = $exchange_rates->convert_to_euro( $currency->get_iso_code(), $amount, 0 );
			}
		}
		?>
		<div class="participation-fee">
			<?php printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s %s.', 'siw' ), $symbol, $amount );?>
			<?php if ( isset( $amount_in_euro ) && ! empty( $amount_in_euro ) ):?>
				&nbsp;<?php printf ( esc_html__( '(Ca. &euro; %s)', 'siw' ), $amount_in_euro ); ?>
			<?php endif ?>
		</div>
		<?php
	}

	/** Toont badge voor aanbevolen projecten */
	public function show_featured_badge() {
		global $product;
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}

}
