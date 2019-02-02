/**
 * @file Function t.b.v. postcode lookup
 * @author Maarten Bruna 
 * @copyright 2015-2018 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Zoekt straat en plaats o.b.v. postcode en huisnummer
 * 
 * @param {string} postcodeSelector 
 * @param {string} housenumberSelector 
 * @param {string} streetSelector 
 * @param {string} citySelector 
 */
function siwPostcodeLookupFromForm( postcodeSelector, housenumberSelector, streetSelector, citySelector ) {
	var postcode = jQuery( postcodeSelector ).val().replace( / /g, '' ).toUpperCase();
	var housenumber = jQuery( housenumberSelector ).val();
	var housenumber = housenumber.replace( /[^0-9]/g, '' );

	if ( ( '' != postcode ) && ( '' != housenumber ) ) {
		siwPostcodeLookup( postcode, housenumber ).done( function( response ) {
			if ( true == response.success ) {
				jQuery( citySelector ).val( response.data.city ).prop( 'readonly', true );
				jQuery( streetSelector ).val( response.data.street ).prop( 'readonly', true );
			}else {
				jQuery( citySelector + ', ' + streetSelector ).val( '' ).prop( 'readonly', false );
			}
		}).fail( function() {
			jQuery( citySelector + ', ' + streetSelector ).val( '' ).prop( 'readonly', false );
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