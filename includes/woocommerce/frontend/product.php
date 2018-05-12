<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Verwijderen diverse woocommerce-hooks
 * - "Reset variations"-link
 * - Prijsrange
 * - Trailing zeroes
 * - Redundante headers in tabs
 * - Meta-informatie (tags, categorie, SKU)
 */
add_action( 'plugins_loaded', function() {
	add_filter( 'woocommerce_reset_variations_link', '__return_false' );
	add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
	add_filter( 'woocommerce_product_description_heading', '__return_false' );
	add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
} );

add_action( 'wp', function() {
	global $product;
	if ( is_product() ) {
		
		$product = wc_get_product();
		if ( ! $product->is_purchasable() ) {
			remove_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation', 10 );
			remove_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation_add_to_cart_button', 20 );
			add_filter( 'woocommerce_variation_is_visible', '__return_false');
		}
	}
}, 99 );

/* Toon local fee indien van toepassing */
add_action( 'woocommerce_after_add_to_cart_form', function() {
	global $product;
	$participation_fee = $product->get_meta( 'participation_fee' );
	$participation_fee_currency = $product->get_meta( 'participation_fee_currency' );
	$local_fee = $product->get_attribute( 'lokale-bijdrage' );
	if ( ! empty( $participation_fee_currency ) && $participation_fee > 0 ) {
		$currency = siw_get_currency( $participation_fee_currency );
		$symbol = isset( $currency['symbol'] ) ? $currency['symbol'] : $participation_fee_currency;
		if ( false != $currency && 'EUR' != $participation_fee_currency ) {
			$amount_in_euro = siw_get_amount_in_euro( $participation_fee_currency, $participation_fee );
		}
		?>
		<div class="local-fee">
			<?php printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s %s.', 'siw' ), $symbol, $participation_fee );?>
			<?php if ( isset( $amount_in_euro ) ):?>
				&nbsp;<?php printf ( esc_html__( '(Ca. &euro; %s)', 'siw' ), $amount_in_euro ); ?>
			<?php endif ?>
		</div>
		<?php
	}
	elseif ( $local_fee ) {
 		?>
		<div class="local-fee">
			<?php printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s.', 'siw' ), $local_fee );?>
		</div>
	<?php
	}
} );


add_filter( 'woocommerce_is_purchasable', function( $is_purchasable, $product ) {
	$is_purchasable = $product->is_visible();
	return $is_purchasable;
}, 10, 2 );


/* Toon voorwaarden voor studententarief als dat geselecteerd is */
add_filter( 'woocommerce_available_variation', function( $variations ) {
	if ( 'student' == $variations['attributes']['attribute_pa_tarief'] ) {
		$variations['variation_description'] =  __( 'Je komt in aanmerking voor het studententarief als je 17 jaar of jonger bent of als je een bewijs van inschrijving kunt laten zien.', 'siw' );
	}
	return $variations;
} );


/* Tekst voor korting-badge*/
add_filter('woocommerce_sale_flash', function ( $text ) {
	return '<span class="onsale">' . __( 'Korting', 'siw' ) . '</span>';
} );


/* Altijd prijs van variatie tonen */
add_filter( 'woocommerce_show_variation_price', '__return_true' );


/* Volgorde van projecteigenschappen aanpassen */
add_filter( 'woocommerce_product_get_attributes', function( $attributes ) {
	$order = array(
		'pa_projectnaam', //TODO: verwijderen indien mogelijk
		'projectnaam',
		'pa_projectcode', //TODO: verwijderen indien mogelijk
		'projectcode',
		'pa_land',
		'pa_soort-werk',
		'pa_startdatum', //TODO: verwijderen indien mogelijk
		'startdatum',
		'pa_einddatum', //TODO: verwijderen indien mogelijk
		'einddatum',
		'pa_aantal-vrijwilligers', //TODO: verwijderen indien mogelijk
		'aantal-vrijwilligers',
		'pa_leeftijd', //TODO: verwijderen indien mogelijk
		'leeftijd',
		'pa_lokale-bijdrage', //TODO: verwijderen indien mogelijk
		'lokale-bijdrage',
		'pa_taal',
		'pa_vog',
		'pa_doelgroep',
	);
	uksort( $attributes, function( $key1, $key2 ) use ( $order ) {
		return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
	} );
	return $attributes;
} );


/* Minder (4 i.p.v. 8) related products tonen */
add_filter( 'woocommerce_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	return $args;
}, 999 );


/*
 * Extra tabs toevoegen:
 * - Contactformulier product
 * - Kaart met projectlocatie
 * Tab verwijderen
 * - Reviews
 */
add_filter( 'woocommerce_product_tabs', function( $tabs ) {

	/*Projectlocatie*/
	global $product;
	$latitude = $product->get_meta( 'latitude' );
	$longitude = $product->get_meta( 'longitude' );

	if ( 0 != $latitude && 0 != $longitude ) {
		$tabs['location'] = array(
			'title'     => __( 'Projectlocatie', 'siw' ),
			'priority'  => 110,
			'callback'  => 'siw_workcamp_show_project_map',
			'latitude'  => $latitude,
			'longitude' => $longitude,
		);
	}
	/*Contactformulier*/
	$tabs['enquiry'] = array(
		'title'    => __( 'Stel een vraag', 'siw' ),
		'priority' => 120,
		'callback' => 'siw_workcamp_show_product_enquiry_form',
	);
	/*review-tab verwijderen*/
	unset( $tabs['reviews'] );

	return $tabs;
}, 999 ,1 );

/**
 * Kaart in producttab tonen
 * @param  array $tab
 * @param  array $args
 * @return void
 */
function siw_workcamp_show_project_map( $tab, $args ) {
	echo do_shortcode( sprintf( '[gmap address="%s,%s" title="%s" zoom="6" maptype="ROADMAP"]', esc_attr( $args['latitude'] ), esc_attr( $args['longitude'] ), esc_attr__( 'Projectlocatie', 'siw' ) ) );
}

/**
 * Contactformulier in producttab tonen
 * @return void
 */
function siw_workcamp_show_product_enquiry_form() {
	echo do_shortcode( '[caldera_form id="contact_project"]' );
}
