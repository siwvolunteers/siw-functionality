/** global: ga, siw_analytics_cart */

/**
 * @file      Functies t.b.v. Google Analytics
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

siwGoogleAnalyticsAddListeners();

/**
 * Voegt listeners toe voor alle links
 */
function siwGoogleAnalyticsAddListeners() {
	
	var tracking_links = document.querySelectorAll( '[data-ga-track="1"]' );
	tracking_links.forEach( function ( el ) {
		el.addEventListener( 'click', siwGoogleAnalyicsSendEvent );
	})

	var remove_from_cart_links = document.querySelectorAll( '.woocommerce-cart-form .product-remove > a' );
	remove_from_cart_links.forEach( function ( el ) {
		el.addEventListener( 'click', siwGoogleAnalyticsTrackRemoveFromCart );
	});
}

/**
 * Verstuurt GA-event op basis van data-attributes
 */
function siwGoogleAnalyicsSendEvent() {
	var type = this.dataset.gaType ? this.dataset.gaType : '';
	var category = this.dataset.gaCategory ? this.dataset.gaCategory : '';
	var action = this.dataset.gaAction ? this.dataset.gaAction : '';
	var label = this.dataset.gaLabel ? this.dataset.gaLabel : '';
	ga( 'send', type, category, action, label);
}

/**
 * Verstuurt GA-event voor verwijderen uit cart
 *
 * @param {Event} event
 */
function siwGoogleAnalyticsTrackRemoveFromCart( event ) {
	event.preventDefault();
	var variation_id = this.dataset.variation_id;
	ga( 'ec:addProduct', siw_analytics_cart[variation_id]);
	ga( 'ec:setAction', 'remove' );
	ga( 'send', 'event', 'Ecommerce', 'remove', 'remove from cart' );	
}

/**
 * Stuurt GA event bij het versturen van een Caldera Form
 *
 * @param {*} obj
 */
function siwSendGaFormSubmissionEvent( obj ) {
	ga( 'send', 'event', obj.form_id, 'Verzenden' );
}
