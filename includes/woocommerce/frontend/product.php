<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Data\Currency;
use SIW\External\Exchange_Rates;
use SIW\Properties;
use SIW\WooCommerce\Product\WC_Product_Project;
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
		add_filter( 'woocommerce_display_product_attributes', [ $self, 'display_product_attributes'], 10, 2 );
		add_action( 'woocommerce_project_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );

		
		add_action( 'woocommerce_single_product_summary', [ $self, 'show_project_summary'], 20 );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'show_local_fee'] );

		add_action( 'woocommerce_before_single_product_summary', [ $self, 'show_featured_badge' ], 10 );
		add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
		
		//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 ); //TODO: studentenkorting tonen
		add_action( 'woocommerce_single_product_summary', [ $self, 'show_student_discount_info'], 10 );
		add_filter('woocommerce_single_product_image_thumbnail_html', [ $self, 'remove_link_on_thumbnails'] );
	}

	/** Verwijdert link bij productafbeelding */
	public function remove_link_on_thumbnails( string $html ): string {
		return strip_tags( $html, '<img>' );
	}

	/** Past weergave van de attributes aan */
	public function display_product_attributes( array $attributes, WC_Product_Project $product ): array {
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
		if ( $product->is_dutch_project() ) {
			unset( $attributes['attribute_lokale-bijdrage']);
		}

		return $attributes;
	}

	public function show_project_summary() {
		global $post;
		$product = \siw_get_product( $post );
		if ( null == $product ) {
			return;
		}

		esc_html_e( 'In het kort:', 'siw' );


		$duration = siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false );
		echo '<p>';
		echo $product->get_country()->get_name() . BR;
		echo implode( ' | ', wc_get_product_terms( $product->get_id(), Taxonomy_Attribute::WORK_TYPE()->value, ['fields' => 'names' ] ) ) . BR;
		echo esc_html( $duration ) . BR;
		echo implode( ' | ', wc_get_product_terms( $product->get_id(), Taxonomy_Attribute::SDG()->value, ['fields' => 'names' ] ) ) . BR;
		echo $product->get_attribute( Product_Attribute::NUMBER_OF_VOLUNTEERS()->value );
		echo '</p>';
		



	}


	/** Toont infotekst over studentenkorting TODO: 1 tarieven blok van maken met tarief en local fee? */
	public function show_student_discount_info() {
		global $post;

		$product = siw_get_product( $post );

		if ( null == $product ) {
			return;
		}
		echo sprintf( esc_html__( 'Exclusief %s studenten/jongerenkorting', 'siw'), siw_format_amount( Properties::STUDENT_DISCOUNT_AMOUNT ) );
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
