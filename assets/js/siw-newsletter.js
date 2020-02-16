/** global: siw_newsletter, ga */

/**
 * @file      Functies t.b.v. de nieuwsbrief signup
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */


if ( document.readyState !== "loading" ) {
	siwNewsletterSubscribeAddListeners();
} else {
	document.addEventListener( 'DOMContentLoaded', siwNewsletterSubscribeAddListeners );
}

/**
 * Voegt listeners voor nieuwsbriefwidgets toe
 *
 */
function siwNewsletterSubscribeAddListeners() {
	var widgets = document.querySelectorAll( '[data-siw-newsletter-selectors]' );

	for ( var i=0, len = widgets.length; i < len; i++ ) {
		var widget = widgets[i];
		var selectors = JSON.parse( widget.dataset.siwNewsletterSelectors );
		var form = document.querySelector( selectors.form );
		form.addEventListener( 'submit', siwNewsletterSubscribeFromForm.bind( null, selectors ) );
	}
}

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
		email : email
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
 * Verwerkt aanmelding voor nieuwsbrief
 *
 * @param {Event} event
 * @param {Array} selectors
 */
function siwNewsletterSubscribeFromForm( selectors, event ) {
	event.preventDefault();

	var name = jQuery( selectors.name ).val();
	var email = jQuery( selectors.email ).val();

	if ( ( '' != name ) && ( '' != email ) ) {
		jQuery( selectors.form ).addClass( 'hidden' );
		jQuery( selectors.loading ).removeClass( 'hidden' );

		siwNewsletterSubscribe( name, email ).done( function( response ) {
			jQuery( selectors.loading ).addClass( 'hidden' );
			jQuery( selectors.message ).removeClass( 'hidden' ).text( response.message );
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

