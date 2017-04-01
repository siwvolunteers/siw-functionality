/*
 * (c)2015-2017 SIW Internationale vrijwilligersprojecten
 */

(function( $ ) {
	$.validator.setDefaults({
		errorPlacement: function( error, element ) {
			error.appendTo( element.parents( 'p' ) );
		}
	});
	$.validator.addMethod( 'dateNL', function( value, element ) {
		return this.optional( element ) || /^(0?[1-9]|[12]\d|3[01])[\-](0?[1-9]|1[012])[\-]([12]\d)?(\d\d)$/.test( value );
	}, parameters.invalid_date );

	$.validator.addMethod( 'postalcodeNL', function( value, element ) {
		return this.optional( element ) || /^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/.test( value );
	}, parameters.invalid_postcode );

	/*Workaround om de betaalmethode te selecteren als op de gestylde radiobuttons geklikt wordt.*/
	$( document ).on( 'click', 'li.payment_method_mollie_wc_gateway_ideal div', function() {
		$( '#payment_method_mollie_wc_gateway_ideal' ).click();
	});
	$( document ).on( 'click', 'li.payment_method_mollie_wc_gateway_ideal .mCheckable', function() {
		$( '#payment_method_mollie_wc_gateway_ideal' ).click();
	});
	$( document ).on( 'click', 'li.payment_method_bacs div', function() {
		$( '#payment_method_bacs' ).click();
	});
	$( document ).on( 'click', 'li.payment_method_bacs .mCheckable', function() {
		$( '#payment_method_bacs' ).click();
	});

	//Styling van checkboxes en radiobuttons + validatie
	$( document ).ready(function() {
		$( 'form.checkout .validate-required select' ).attr( 'required', 'required' );
		$( 'form.checkout .validate-required #billing_dob' ).addClass( 'dateNL' );
		$( 'form.checkout .validate-required #billing_postcode' ).addClass( 'postalcodeNL' );
		$( 'form.checkout .validate-required .input-radio' ).attr( 'required', 'required' );

		//WooCommerce checkout page
		$( '.woocommerce-billing-fields :radio' ).addClass( 'mCheck' );
		$( '.woocommerce-extra-fields :radio' ).addClass( 'mCheck' );

		//WooCommerce mailpoet opt-in
		$( '#newsletter_signup' ).addClass( 'mCheck' );

		//Uitvoeren
		$( '.mCheck' ).mCheckable({ innerTags: '<div></div>' });
	});

	$( document ).ajaxComplete(function() {

		//WooCommerce betaalmethodes
		$( '.woocommerce-checkout-payment :radio' ).addClass( 'mCheckAjax' );
		$( '#terms' ).addClass( 'mCheckAjax' );

		//Uitvoeren
		$( '.mCheckAjax' ).mCheckable({ innerTags: '<div></div>' });

	});

	$( document ).on( 'change', '#billing_postcode, #billing_housenumber', function() {
		var postcode = $( '#billing_postcode' ).val().replace( / /g, '' ).toUpperCase();
		var housenumber = $( '#billing_housenumber' ).val();
		var housenumber = housenumber.replace( /[^0-9]/g, '' );
		if ( ( '' != postcode ) && ( '' != housenumber ) ) {
			$.ajax({
				url: parameters.ajax_url,
				type: 'get',
				dataType: 'json',
				data: {
					action: 'postcode_lookup',
					postcode: postcode,
					housenumber: housenumber
				},
				success: function( result ) {
					if ( 1 == result.success ) {
						$( '#billing_city' ).val( result.resource.town );
						$( '#billing_address_1' ).val( result.resource.street );
						$( '#billing_city' ).prop( 'readonly', true );
						$( '#billing_address_1' ).prop( 'readonly', true );
					}else {
						$( '#billing_city' ).val( '' );
						$( '#billing_address_1' ).val( '' );
						$( '#billing_city' ).prop( 'readonly', false );
						$( '#billing_address_1' ).prop( 'readonly', false );
					}
				}
			});
		}
		return false;
    });

})( jQuery );
