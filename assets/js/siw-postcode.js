/** global: siw_postcode */

/**
 * @file      Function t.b.v. postcode lookup
 * @copyright 2015-2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

if ( document.readyState !== "loading" ) {
	siwPostcodeLookupAddListeners();
} else {
	document.addEventListener( 'DOMContentLoaded', siwPostcodeLookupAddListeners );
}

/**
 * Voegt listeners toe voor alle links
 */
function siwPostcodeLookupAddListeners() {
	
	var forms = document.querySelectorAll( '[data-siw-postcode-lookup="1"]' );
	for ( var i=0, len = forms.length; i < len; i++ ) {
		var form = forms[i];
		var selectors = JSON.parse( form.dataset.siwPostcodeSelectors );
		var postcode = document.querySelector( selectors.postcode );
		var housenumber = document.querySelector( selectors.housenumber );

		postcode.addEventListener( 'change', siwPostcodeLookupFromForm.bind( null, selectors ) );
		housenumber.addEventListener( 'change', siwPostcodeLookupFromForm.bind( null, selectors ) );
	}

	if ( typeof siw_checkout_postcode_selectors !== 'undefined') {
		var selectors = siw_checkout_postcode_selectors;
		var postcode = document.querySelector( selectors.postcode );
		var housenumber = document.querySelector( selectors.housenumber );
		postcode.addEventListener( 'change', siwPostcodeLookupFromForm.bind( null, selectors ) );
		housenumber.addEventListener( 'change', siwPostcodeLookupFromForm.bind( null, selectors ) );
	}
}

/**
 * Zoekt straat en plaats o.b.v. postcode en huisnummer
 *
 * @param {array} selectors
 * @returns {bool}
 */
function siwPostcodeLookupFromForm( selectors ) {
	var postcode = jQuery( selectors.postcode ).val().replace( / /g, '' ).toUpperCase();
	var housenumber = jQuery( selectors.housenumber ).val();
	housenumber = housenumber.replace( /[^0-9]/g, '' );

	if ( ( '' != postcode ) && ( '' != housenumber ) ) {
		siwPostcodeLookup( postcode, housenumber ).done( function( response ) {
			if ( true === response.success ) {
				jQuery( selectors.city ).val( response.data.city ).prop( 'readonly', true );
				jQuery( selectors.street ).val( response.data.street ).prop( 'readonly', true );
			}else {
				jQuery( selectors.city  + ', ' + selectors.street ).val( '' ).prop( 'readonly', false );
			}
		}).fail( function() {
			jQuery( selectors.city  + ', ' + selectors.street ).val( '' ).prop( 'readonly', false );
		});
	}

	return false; 
}

/**
 * Zoekt adres op via API endpoint
 *
 * @param {string} postcode
 * @param {int} housenumber
 * @returns
 */
function siwPostcodeLookup( postcode, housenumber ) {
	var data = {
		housenumber: housenumber,
		postcode: postcode
	};

	return jQuery.ajax({
		method: 'GET',
		url: siw_postcode.api_url,
		data: data,
		beforeSend : function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', siw_postcode.api_nonce );
		},
	});
}