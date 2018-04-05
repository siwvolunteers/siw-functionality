<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Toevoegen custom jQuery-functies */
add_action( 'wp_enqueue_scripts', function() {
	wp_register_script( 'siw', SIW_ASSETS_URL . 'js/siw.js', array( 'jquery' ), SIW_PLUGIN_VERSION, true );
	$parameters = array(
		'ajax_url'		=> SIW_AJAX_URL,
		'ajax_nonce'	=> wp_create_nonce( 'siw_ajax_nonce' ),
		'invalid_email'	=> __( 'Dit is geen geldig e-mailadres', 'siw' ),
	);
	wp_localize_script( 'siw', 'siw', $parameters );
	wp_enqueue_script( 'siw' );

	wp_register_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js' );
});


/* Toevoegen custom scripts voor checkout */
add_action( 'wp_enqueue_scripts', function() {
	wp_register_script( 'siw-checkout', SIW_ASSETS_URL . 'js/siw-checkout.js', array( 'jquery' ), SIW_PLUGIN_VERSION, true );
	$parameters = array(
		'ajax_url'			=> SIW_AJAX_URL,
		'invalid_postcode'	=> __( 'Dit is geen geldige postcode.', 'siw' ),
		'invalid_date'		=> __( 'Dit is geen geldige datum.', 'siw' ),
	);
	wp_localize_script( 'siw-checkout', 'siwCheckout', $parameters );
	if ( is_checkout() ) {
		wp_enqueue_script( 'siw-checkout' );
	}
});


/* Voeg styling voor admin toe */
add_action( 'admin_enqueue_scripts', function() {
	wp_register_style( 'siw-admin', SIW_ASSETS_URL . 'css/siw-admin.css' );
	wp_enqueue_style( 'siw-admin' );
});



/* Functies om scripts alleen te laden indien nodig */
add_action( 'wp_enqueue_scripts', function() {
	/*variatie als radiobuttons*/
	if ( ! is_product() ) {
		wp_dequeue_script( 'kt-wc-add-to-cart-variation-radio' );
	}

	/*woocommerce ajax filter*/
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! taxonomy_is_product_attribute( get_query_var( 'taxonomy' ) ) ) {
		wp_dequeue_script( 'yith-wcan-script' );
		wp_dequeue_style( 'yith-wcan-frontend' );
	}

	/*Mailpoet*/
	wp_deregister_style( 'validate-engine-css' );

}, 999 );
