<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/np-camp-leader.php' );
require_once( __DIR__ . '/np-cooperation.php' );


/* Opties telefoonveld aanpassen */
add_filter( 'caldera_forms_phone_js_options', function( $options ) {
	$options['initialCountry'] = 'nl';
	$options['preferredCountries'] = array('nl');
	return $options;
}, 10 );

/*extra span voor styling radiobuttons en checkboxen*/
add_filters( array('caldera_forms_render_field_type-checkbox', 'caldera_forms_render_field_type-radio'), function( $field_html ) {
	$field_html = preg_replace( '/<input(.*?)>/', '<input$1><div class="control-indicator"></div>', $field_html );
	return $field_html;
} );


/* Gebruik label van radiobuttons en checkboxes in mail*/
add_filters( 'caldera_forms_magic_summary_should_use_label', '__return_true' );

/* Patroon voor samenvatting*/
add_filters( 'caldera_forms_summary_magic_pattern', function( $pattern ) {
	$pattern = '<tr>
		<td width="35%%" style="font-family:\'Open Sans\', Verdana, normal; color:#444; font-size:0.8em;">%s</td>
		<td width="5%%"></td>
		<td width="50%%" style="font-family:\'Open Sans\', Verdana, normal; color:#444; font-size:0.8em; font-style:italic">%s</td>
	</tr>';
	return $pattern;
} );
