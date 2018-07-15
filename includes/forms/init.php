<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Velden */
require_once( __DIR__ . '/fields.php' );

/* Formulieren */
require_once( __DIR__ . '/enquiry-evs.php' );
require_once( __DIR__ . '/enquiry-general.php' );
require_once( __DIR__ . '/enquiry-workcamp.php' );
require_once( __DIR__ . '/evs.php' );
require_once( __DIR__ . '/info-day.php' );
require_once( __DIR__ . '/camp-leader.php' );
require_once( __DIR__ . '/cooperation.php' );
require_once( __DIR__ . '/op-maat.php' );



/* Geen revisies */
add_filter( 'caldera_forms_save_revision', '__return_false' );


/*extra span voor styling radiobuttons en checkboxen*/
add_filters( array('caldera_forms_render_field_type-checkbox', 'caldera_forms_render_field_type-radio', 'caldera_forms_render_field_type-gdpr' ), function( $field_html ) {
	$field_html = preg_replace( '/<input(.*?)>/s', '<input$1><div class="control-indicator"></div>', $field_html );
	return $field_html;
} );


/* Gebruik label van radiobuttons en checkboxes in mail*/
add_filter( 'caldera_forms_magic_summary_should_use_label', '__return_true' );


/* Magic tags in links toestaan in wp_kses_post */
add_filter( 'kses_allowed_protocols', function( $protocols ) {
	$protocols[] = '{embed_post';
	return $protocols;
} );


/* Patroon voor samenvatting*/
add_filter( 'caldera_forms_summary_magic_pattern', function( $pattern ) {
	$pattern = '<tr>
		<td width="35%%" style="font-family: Verdana, normal; color:' . SIW_FONT_COLOR . '; font-size:0.8em;">%s</td>
		<td width="5%%"></td>
		<td width="50%%" style="font-family: Verdana, normal; color:' . SIW_FONT_COLOR . '; font-size:0.8em; font-style:italic">%s</td>
	</tr>';
	return $pattern;
} );


/* wpautop verwijderen van mail */
add_action( 'plugins_loaded', function() {
	if ( ! class_exists( 'Caldera_Forms' ) ) {
		return;
	}
	remove_filter( 'caldera_forms_mailer', array( Caldera_Forms::get_instance(), 'format_message' ) );
	remove_filter( 'caldera_forms_autoresponse_mail', array( 'Caldera_Forms_Email_Filters', 'format_autoresponse_message' ) );
});


/* Extra validaties toevoegen */
add_filter( 'caldera_forms_field_attributes', function( $attrs, $field ) {

	if ( 'geboortedatum' === $field['ID'] ) {
		$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige datum.', 'siw' );
		$attrs[ 'data-parsley-pattern' ] = siw_get_regex( 'date' );
	}

	if ( 'postcode' === $field['ID'] ) {
		$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige postcode.', 'siw' );
		$attrs[ 'data-parsley-pattern' ] = siw_get_regex( 'postal_code' );
	}

	return $attrs;
}, 10, 2 );


/* Shortcode-knop voor Caldera Forms niet meer tonen */
add_action( 'plugins_loaded', function() {
	if ( ! class_exists( 'Caldera_Forms_Admin' ) ) {
		return;
	}
	remove_action( 'media_buttons', array( Caldera_Forms_Admin::get_instance(), 'shortcode_insert_button' ), 11 );
}, 15 );

add_filter( 'caldera_forms_insert_button_include', '__return_false' );
