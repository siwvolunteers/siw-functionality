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


/* Toon local fee indien van toepassing */
add_action( 'woocommerce_after_add_to_cart_form', function() {
	global $product;
	$local_fee = $product->get_attribute( 'lokale-bijdrage' );
	if ( $local_fee ) {
		echo '<div class="local-fee">';
		printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s.', 'siw' ), $local_fee );
		echo '</div>';
	}
} );


/* Toon voorwaarden voor studententarief als dat geselecteerd is */
add_filter( 'woocommerce_available_variation', function( $variations ) {
	if ( 'student' == $variations['attributes']['attribute_pa_tarief'] ) {
		$variations['variation_description'] =  __( 'Je komt in aanmerking voor het studententarief als je 17 jaar of jonger bent of als je een bewijs van inschrijving kunt laten zien.', 'siw' );
	}
	return $variations;
} );


/* Volgorde van projecteigenschappen aanpassen TODO: sortable optie maken */
add_filter( 'woocommerce_get_product_attributes', function( $attributes ) {
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


/*
 * Extra tabs toevoegen:
 * - Contactformulier product
 * - Kaart met projectlocatie
 * Tab verwijderen
 * - Reviews
 */
add_filter( 'woocommerce_product_tabs', function( $tabs ) {
	//Contactformulier
	$tabs['enquiry'] = array(
		'title'    => __( 'Stel een vraag', 'siw' ),
		'priority' => 100,
		'callback' => 'siw_workcamp_show_product_enquiry_form',
		'random'   => 'hoi',
	);

	//Projectlocatie
	global $product;
	$latitude = get_post_meta( $product->id, 'latitude', true );
	$longitude = get_post_meta( $product->id, 'longitude', true );
	if ( 0 != $latitude && 0 != $longitude ) {
		$tabs['location'] = array(
			'title'     => __( 'Projectlocatie', 'siw' ),
			'priority'  => 110,
			'callback'  => 'siw_workcamp_show_project_map',
			'latitude'  => $latitude,
			'longitude' => $longitude,
		);
	}

	//review-tab verwijderen
	unset( $tabs['reviews'] );

	return $tabs;
}, 999 ,1 );

/* Kaart tonen op basis van coördinaten */
function siw_workcamp_show_project_map( $tab, $args ) {
	echo do_shortcode( sprintf( '[gmap address="%s,%s" title="Projectlocatie" zoom="7" maptype="ROADMAP"]',esc_attr( $args['latitude'] ), esc_attr( $args['longitude'] ) ) );
}

/* Contactformulier product tonen TODO: aanpassen ivm switch naar Caldera Forms */
function siw_workcamp_show_product_enquiry_form() {
	$contact_form_id = siw_get_cf7_form_id( 'project' );
	echo do_shortcode(sprintf( '[contact-form-7 id="%d"]',esc_attr( $contact_form_id ) ) );
}
