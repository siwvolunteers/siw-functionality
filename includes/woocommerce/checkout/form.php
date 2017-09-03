<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Verbergen opmerkingenveld */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


/* Voorkomen dat WooCommerce het postcodeveld steeds verplaatst. */
add_filter( 'woocommerce_get_country_locale', function( $fields ) {
	$fields['NL']['postcode_before_city'] = false;
	$locale['NL']['postcode']['priority'] = 50;
	return $fields;
}, 99);


/* Verwijderen verzendadres */
add_filter( 'woocommerce_shipping_fields', '__return_empty_array' );


/* Volgorde adresvelden aanpassen */
add_filter( 'woocommerce_default_address_fields', function( $fields ) {
	/* Sorteren velden */
	$address_fields = siw_sort_customer_address_fields( $fields );

	/*pas eigenschappen van standaardvelden aan*/
	$address_fields['gender']['class'] = array( 'form-row-last' );
	$address_fields['gender']['label_class'] = array( 'radio-label' );
	$address_fields['dob']['class'] = array( 'form-row-first' );
	$address_fields['dob']['placeholder'] = __( 'dd-mm-jjjj', 'siw' );
	$address_fields['nationality']['class'] = array( 'form-row-last', 'select' );
	$address_fields['nationality']['label_class'] = array( 'select-label');
	$address_fields['housenumber']['class'] = array( 'form-row-last' );
	$address_fields['postcode']['class'] = array( 'form-row-first' );
	$address_fields['postcode']['placeholder'] = __( '1234 AB', 'siw' );
	$address_fields['address_1']['class'] = array( 'form-row-first' );
	$address_fields['address_1']['placeholder'] = '';
	$address_fields['city']['class'] = array( 'form-row-last' );
	$address_fields['country']['class'] = array( 'form-row-first', 'country', 'select' );
	$address_fields['country']['label_class'] = array( 'select-label');
	$address_fields['country']['description'] = __( 'Het is alleen mogelijk om je aan te melden als je in Nederland woont.', 'siw' );

	return $address_fields;
} );


/* Filter JS-selectors */
add_filter( 'woocommerce_country_locale_field_selectors', function( $locale_fields ) {
	unset( $locale_fields['address_2'] );
	unset( $locale_fields['state'] );
	return $locale_fields;
});


/*
 * Toevoegen extra velden
 * - Vragen voor partner
 * - Talenkennis
 */
add_action( 'woocommerce_multistep_checkout_before_order_info', function( $checkout ) {
	//TODO: velden verplaatsen naar generieke functie i.v.m. herbruikbaarheid

	$languages = siw_get_volunteer_languages();
	$language_skill = siw_get_volunteer_language_skill_levels();
	?>
	<h1><?php esc_html_e( 'Informatie voor partner', 'siw' );?></h1>
	<div class="woocommerce-extra-fields">
		<div id="infoForPartner"><h3><?php esc_html_e( 'Informatie voor partnerorganisatie', 'siw' );?></h3>
		<?php
		woocommerce_form_field( 'motivation', array(
			'type'			=> 'textarea',
			'class'			=> array( 'form-row-first' ),
			'required'		=> true,
			'label'			=> __( 'Motivation', 'siw' ),
			'placeholder'	=> __( 'Vul hier (in het Engels) in waarom je graag aan je gekozen project wil deelnemen.', 'siw' ),
			), $checkout->get_value( 'motivation' )
		);
		woocommerce_form_field( 'healthIssues', array(
			'type'			=> 'textarea',
			'class'			=> array( 'form-row-last' ),
			'required'		=> false,
			'clear'			=> true,
			'label'			=> __( 'Allergies/diet/health issues', 'siw' ),
			'placeholder'	=> __( 'Heb je een allergie, gebruik je medicijnen of volg je een diëet, vul dat dan hier in (in het Engels).', 'siw' ),
			), $checkout->get_value( 'healthIssues' )
		);
		woocommerce_form_field( 'volunteerExperience', array(
			'type'			=> 'textarea',
			'class'			=> array( 'form-row-first' ),
			'required'		=> false,
			'label'			=> __( 'Volunteer experience', 'siw' ),
			'placeholder'	=> __( 'Heb je eerder vrijwilligerswerk gedaan? Beschrijf dat dan hier (in het Engels).', 'siw' ),
			), $checkout->get_value( 'volunteerExperience' )
		);
		woocommerce_form_field( 'togetherWith', array(
			'type'			=> 'text',
			'class'			=> array( 'form-row-last' ),
			'required'		=> false,
			'clear'			=> true,
			'label'			=> __( 'Together with', 'siw' ),
			'placeholder'	=> __( 'Wil je graag met iemand aan een project deelnemen. Vul zijn of haar naam dan hier in.', 'siw' ),
			), $checkout->get_value( 'togetherWith' )
		);?>
		</div>
		<div id="emergencyContact"><h3><?php esc_html_e( 'Noodcontact', 'siw' );?></h3>
		<?php
		woocommerce_form_field( 'emergencyContactName', array(
			'type'			=> 'text',
			'class'			=> array( 'form-row-first' ),
			'required'		=> true,
			'label'			=> __( 'Naam', 'siw' ),
			), $checkout->get_value( 'emergencyContactName' )
		);
		woocommerce_form_field( 'emergencyContactPhone', array(
			'type'			=> 'tel',
			'class'			=> array( 'form-row-last' ),
			'required'		=> true,
			'label'			=> __( 'Telefoonnummer', 'siw' ),
			'clear'			=> true
			), $checkout->get_value( 'emergencyContactPhone' )
		);?>
		</div>
		<div id="languageSkills">
			<h3><?php esc_html_e( 'Talenkennis', 'siw' );?></h3>
		<?php
		woocommerce_form_field( 'language1', array(
			'type'			=> 'select',
			'class'			=> array( 'form-row-first', 'select' ),
			'label_class'	=> array( 'select-label' ),
			'label'			=> __( 'Taal 1', 'siw' ),
			'required'		=> true,
			'options'		=> $languages
			), $checkout->get_value( 'language1' )
		);
		woocommerce_form_field( 'language1Skill', array(
			'type'			=> 'radio',
			'class'			=> array( 'form-row-last' ),
			'label_class'	=> array( 'radio-label'),
			'label'			=> __( 'Niveau taal 1', 'siw' ),
			'required'		=> true,
			'clear'			=> true,
			'options'		=> $language_skill
			), $checkout->get_value( 'language1Skill' )
		);
		woocommerce_form_field( 'language2', array(
			'type'			=> 'select',
			'class'			=> array( 'form-row-first', 'select' ),
			'label_class'	=> array( 'select-label' ),
			'label'			=> __( 'Taal 2', 'siw' ),
			'required'		=> false,
			'options'		=> $languages
			), $checkout->get_value( 'language2' )
		);
		woocommerce_form_field( 'language2Skill', array(
			'type'			=> 'radio',
			'class'			=> array( 'form-row-last' ),
			'label_class'	=> array( 'radio-label'),
			'label'			=> __( 'Niveau taal 2', 'siw' ),
			'required'		=> false,
			'clear'			=> true,
			'options'		=> $language_skill
			), $checkout->get_value( 'language2Skill' )
		);
		woocommerce_form_field( 'language3', array(
			'type'			=> 'select',
			'class'			=> array( 'form-row-first', 'select' ),
			'label_class'	=> array( 'select-label' ),
			'label'			=> __( 'Taal 3', 'siw' ),
			'required'		=> false,
			'options'		=> $languages,
			), $checkout->get_value( 'language3' )
		);
		woocommerce_form_field( 'language3Skill', array(
			'type'			=> 'radio',
			'class'			=> array( 'form-row-last' ),
			'label_class'	=> array( 'radio-label'),
			'label'			=> __( 'Niveau taal 3', 'siw' ),
			'required'		=> false,
			'clear'			=> true,
			'options'		=> $language_skill
			), $checkout->get_value( 'language3Skill' )
		);?>
		</div>
	</div>
<?php
} );

/* Aanpassen radiobuttons en checkboxes ivm styling*/
add_filters( array('woocommerce_form_field_radio', 'woocommerce_form_field_checkbox'), function( $field ) {
	$field = preg_replace( '/<input(.*?)>/', '<input$1><span class="control-indicator"></span>', $field );
	return $field;
}, 10 );

add_filter( 'woocommerce_form_field_args', function( $args ) {
	if ( $args['type'] == 'radio' ) {
		$args['class'][] = 'control-radio';
	}
	if ( $args['type'] == 'checkbox' ) {
		$args['class'][] = 'control-checkbox';
	}
	return $args;

}, 10 );
