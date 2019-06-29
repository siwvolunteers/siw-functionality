/** global: siw_newsletter */

/**
 * @file      Functies t.b.v. de nieuwsbrief signup
 * @author    Maarten Bruna 
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */


/**
 * Verstuurt aanmelding voor nieuwsbrief naar API-endpoint
 *
 * @param {string} postcode
 * @param {int} housenumber
 * @returns
 */
function siwNewsletterSubscribe( name, email ) {
	var data = {
		name : name,
		email : email,
	};

	return jQuery.ajax({
		method: 'POST',
		url: siw_newsletter.api_url,
		data: data,
		beforeSend : function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', siw_newsletter.api_nonce );
		},
	});
}

/**
 * Verwerkt aanmelding voor nieuwsbrief via formulier
 *
 * @param {*} nameSelector
 * @param {*} emailSelector
 */
function siwNewsletterSubscribeFromForm( selector ) {
	var name = jQuery( selector + ' form input[name=\'name\']' ).val();
	var email = jQuery( selector + ' form input[name=\'email\']' ).val();

	if ( ( '' != name ) && ( '' != email ) ) {
		jQuery( selector + ' form' ).addClass( 'hidden' );
		jQuery( selector + ' .loading' ).removeClass( 'hidden' );

		siwNewsletterSubscribe( name, email ).done( function( response ) {
			jQuery( selector + ' .loading' ).addClass( 'hidden' );
			jQuery( selector + ' .message' ).removeClass( 'hidden' ).text( response.message );
			if ( true === response.success ) {
				if ( 'function' == typeof ga ) {
					ga( 'send', 'event', 'Nieuwsbrief', 'Aanmelden' );
				}
			}
		}).fail( function() {
			//TODO
		});
	}
}

