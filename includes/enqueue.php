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
});

/**
 * Toevoegen SIW stylesheet
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_register_style( 'siw', SIW_ASSETS_URL . 'css/siw.css', null, SIW_PLUGIN_VERSION );
	wp_enqueue_style( 'siw' );

	wp_register_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js' );
});

/* Extra instellingen voor jQuery validation*/
add_action( 'wp_enqueue_scripts', function() {

	/* Element voor validation messages aanpassen */
	$inline_script = "$.validator.setDefaults({
		errorPlacement: function( error, element ) {
			error.appendTo( element.parents( 'p' ) );
		}
	})";

	/* Datum-validatie */
	$date_regex = siw_get_regex( 'date' );
	$invalid_date_message = __( 'Dit is geen geldige datum.', 'siw' );

	$inline_script .= sprintf("	
	$.validator.addMethod( 'dateNL', function( value, element ) {
		return this.optional( element ) || %s.test( value );
	}, '%s' );", $date_regex, esc_html( $invalid_date_message ) );

	/* Postcode-validatie */
	$postal_code_regex = siw_get_regex( 'postal_code' );
	$invalid_postal_code_message = __( 'Dit is geen geldige postcode.', 'siw' );
	$inline_script .= sprintf("
	$.validator.addMethod( 'postalcodeNL', function( value, element ) {
		return this.optional( element ) || %s.test( value );
	}, '%s' );", $postal_code_regex, esc_html( $invalid_postal_code_message ) );

	wp_add_inline_script( 'jquery-validate', "(function( $ ) {" .	$inline_script . "})( jQuery );" );
}, 99 );


/* Extra javascript voor checkout */
add_action( 'wp_enqueue_scripts', function() {
	$inline_script = "
	$( document ).on( 'change', '#billing_postcode, #billing_housenumber', function() {
		siwPostcodeLookup( '#billing_postcode', '#billing_housenumber', '#billing_address_1', '#billing_city' );
		return false;
	});";
	
	$inline_script .= "
	$( document ).on( 'click', '#open-terms-and-conditions', function() {
		$('#siw-terms').modal();
		return false
	});";
	wp_add_inline_script( 'wc-checkout', "(function( $ ) {" .	$inline_script . "})( jQuery );" );
}, 99 );


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

	/*Mailpoet*/
	wp_deregister_style( 'validate-engine-css' );

}, 999 );
