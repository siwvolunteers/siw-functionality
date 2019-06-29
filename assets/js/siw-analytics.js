/** global: ga */

/**
 * @file      Functies t.b.v. Google Analytics
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Stuurt GA event bij het versturen van een Caldera Form
 *
 * @param {*} obj
 */
function siwSendGaFormSubmissionEvent( obj ) {
	ga( 'send', 'event', obj.form_id, 'Verzenden' );
}

/* GA-events op basis van data-attributes */
(function( $ ) {
	$('[data-ga-track="1"]').on( 'click', function() {
		var type = $( this ).data('ga-type') ? $( this ).data('ga-type') : '';
		var category = $( this ).data('ga-category') ? $( this ).data('ga-category') : '';
		var action = $( this ).data('ga-action') ? $( this ).data('ga-action') : '';
		var label = $( this ).data('ga-label') ? $( this ).data('ga-label') : '';
	
		ga( 'send', type, category, action, label);
	});
	
	$( document ).on( 'click', '.woocommerce-cart-form .product-remove > a', function( event ) {
		event.preventDefault();
		var variation_id = $( this ).data('variation_id');
		ga('ec:addProduct', siw_analytics_cart[variation_id]);
		ga('ec:setAction', 'remove');
		ga('send', 'event', 'Ecommerce', 'remove', 'remove from cart');
	});

})( jQuery );
