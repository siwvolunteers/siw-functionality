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
	}, siwCheckout.invalid_date );

	$.validator.addMethod( 'postalcodeNL', function( value, element ) {
		return this.optional( element ) || /^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/.test( value );
	}, siwCheckout.invalid_postcode );

	//Extra validatie
	$( document ).ready(function() {
		$( 'form.checkout .validate-required select' ).attr( 'required', 'required' );
		$( 'form.checkout .validate-required #billing_dob' ).addClass( 'dateNL' );
		$( 'form.checkout .validate-required #billing_postcode' ).addClass( 'postalcodeNL' );
		$( 'form.checkout .validate-required .input-radio' ).attr( 'required', 'required' );
	});

	$( document ).on( 'change', '#billing_postcode, #billing_housenumber', function() {
		siwPostcodeLookup( '#billing_postcode', '#billing_housenumber', '#billing_address_1', '#billing_city' );
		return false;
	});

})( jQuery );
