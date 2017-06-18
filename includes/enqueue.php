<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Toevoegen custom jQuery-functies */
add_action( 'wp_enqueue_scripts', function() {
	wp_register_script( 'siw', SIW_ASSETS_URL . 'js/siw.js', array( 'jquery' ), SIW_PLUGIN_VERSION, TRUE);
	$parameters = array(
		'ajax_url'		=> SIW_AJAX_URL,
		'invalid_email'	=> __( 'Vul een geldig e-mailadres in.', 'siw' ),
	);
	wp_localize_script( 'siw', 'parameters', $parameters );
	wp_enqueue_script( 'siw' );

});


/* Toevoegen custom scripts voor checkout */
add_action( 'wp_enqueue_scripts', function() {
	wp_register_script( 'siw-checkout', SIW_ASSETS_URL . 'js/siw-checkout.js', array( 'jquery' ), SIW_PLUGIN_VERSION, TRUE);
	$parameters = array(
		'ajax_url'			=> SIW_AJAX_URL,
		'invalid_postcode'	=> __( 'Dit is geen geldige postcode', 'siw' ),
		'invalid_date'		=> __( 'Dit is geen geldige datum', 'siw' ),
	);
	wp_localize_script( 'siw-checkout', 'parameters', $parameters );
	if ( is_checkout() ) {
		wp_enqueue_script( 'siw-checkout' );
	}
});


/* Voeg styling voor admin toe */
add_action( 'admin_enqueue_scripts', function() {
	wp_register_style( 'siw-admin', SIW_ASSETS_URL . 'css/siw-admin.css' );
	wp_enqueue_style( 'siw-admin' );
});

/* Voeg styling toe voor dashboard widgets */
add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( 'index.php' != $hook ) {
		return;
	}
	wp_enqueue_style( 'siw-dashboard-widgets', SIW_ASSETS_URL . 'css/siw-dashboard-widgets.css' );
});


/* Functies om scripts alleen te laden indien nodig */
add_action( 'wp_enqueue_scripts', function() {
	/*variatie als radiobuttons*/
	if ( ! is_product() ) {
		wp_dequeue_script( 'kt-wc-add-to-cart-variation-radio' );
	}

	/*woocommerce ajax filter*/
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		wp_dequeue_script( 'yith-wcan-script' );
		wp_dequeue_style( 'yith-wcan-frontend' );
	}

	/*woocommerce*/
	wp_dequeue_script( 'woocommerce' );

	/*search&filter*/
	wp_dequeue_style( 'search-filter-plugin-styles' );
	wp_deregister_script( 'search-filter-plugin-chosen' );
	wp_deregister_script( 'jquery-ui-datepicker' );

	/*ncf font*/
	wp_dequeue_style( 'ncf_lato_font' );

	/*styling van mailpoet widget*/
	wp_deregister_style( 'validate-engine-css' );
}, 999 );
