<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Data\Currency;
use SIW\External\Exchange_Rates;
use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Taxonomy_Attribute;
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
		add_filter( 'woocommerce_is_purchasable', [ $self, 'set_product_is_purchasable'], 10, 2 );
		add_filter( 'woocommerce_available_variation', [ $self, 'set_variation_description'] );
		add_filter( 'woocommerce_display_product_attributes', [ $self, 'display_product_attributes'], 10, 2 );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'show_local_fee'] );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'payment_alert'] );	/* tidelijke alert text na aanmelden knop */
		add_filter( 'woocommerce_dropdown_variation_attribute_options_args', [ $self, 'set_variation_dropdown_args'] );

		add_action( 'woocommerce_before_single_product_summary', [ $self, 'show_featured_badge' ], 10 );

		/**
		 * Verwijderen diverse woocommerce-hooks
		 * - "Reset variations"-link
		 * - Prijsrange
		 * - Trailing zeroes
		 * - Redundante headers in tabs
		 * Altijd prijs van variatie tonen
		 */
		add_filter( 'woocommerce_reset_variations_link', '__return_empty_string' );
		add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
		add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	}

	/** Bepaalt of product bestelbaar is */
	public function set_product_is_purchasable( bool $is_purchasable, \WC_Product $product ): bool {
		$is_purchasable = $product->is_visible();
		$status = $product->get_status();

		if ( ! $is_purchasable || Import_Product::REVIEW_STATUS == $status ) {
			add_filter( 'woocommerce_variation_is_visible', '__return_false');
		}
		return $is_purchasable;
	}

	/** Zet de toelichting voor het studententarief */
	public function set_variation_description( $variations ): array {
		if ( 'student' == $variations['attributes']['attribute_pa_tarief'] ) {
			$variations['variation_description'] =  __( 'Je komt in aanmerking voor het studententarief als je 17 jaar of jonger bent of als je een bewijs van inschrijving kunt laten zien.', 'siw' );
		}
		return $variations;
	}

	/** Past weergave van de attributes aan */
	public function display_product_attributes( array $attributes, \WC_Product $product ): array {
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
			return ( array_search( $key1, $order ) <=> array_search( $key2, $order ) );
		} );

		//Local fee verbergen voor nederlandse projecten
		if ( 'nederland' === $product->get_meta( 'country' ) ) {
			unset( $attributes['attribute_lokale-bijdrage']);
		}

		return $attributes;
	}
	/**
	 * tijdelijke tekst voor post corona tijd
	 */
	public function payment_alert()
	{
		$html = '';
		$html .= '<b>'. __('BELANGRIJK:','siw') . '</b>';
		$html .= __('Je kan je gerust aanmelden.','siw') . BR;
		$html .= __('Wij kijken dan of het project inderdaad doorgaat en of je geplaatst kan worden.','siw') . BR;
		$html .= __('Pas als het zeker is dat je ook daadwerkelijk naar de plaats van bestemming kan afreizen, doe je de betaling aan SIW. Niet eerder!','siw');
		echo $html;
	}

	/** Toont lokale bijdrage indien van toepassing */
	public function show_local_fee() {
		global $product;

		//Local fee niet tonen voor nederlandse projecten
		if ( 'nederland' === $product->get_meta( 'country' ) ) {
			return;
		}

		$amount = (float) $product->get_meta( 'participation_fee' );
		$currency_code = $product->get_meta( 'participation_fee_currency' );

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

	/** Zet CSS-klass voor dropdown */
	public function set_variation_dropdown_args( array $args ): array {
		$args['show_option_none'] = __( 'Kies een tarief', 'siw' );
		$args['class'] = 'select-css';
		return $args;
	}

	/**
	 * Toont badge voor aanbevolen projecten
	 * 
	 * @todo template van maken i.v.m. duplicate code in archive
	 */
	public function show_featured_badge() {
		global $product;
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}
}
