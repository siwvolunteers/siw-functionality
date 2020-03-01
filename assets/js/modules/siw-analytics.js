/** global: ga, siw_analytics_cart */

/**
 * @file      Functies t.b.v. Google Analytics
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwGoogleAnalytics = (function () {

	/* Public methodes */
	return {
		init: init,
		trackFormSubmission: trackFormSubmission
	};

	/**
	 * Voegt listeners toe
	 */
	function init() {

		var tracking_links = document.querySelectorAll( '[data-ga-track="1"]' );
		for ( var i=0, len = tracking_links.length; i < len; i++ ) {
			var tracking_link = tracking_links[i];
			tracking_link.addEventListener( 'click', _trackClick );
		}

		var remove_from_cart_links = document.querySelectorAll( '.woocommerce-cart-form .product-remove > a' );
		for ( var i = 0, len = remove_from_cart_links.length; i < len; i++ ) {
			var remove_from_cart_link = remove_from_cart_links[i];
			remove_from_cart_link.addEventListener( 'click', _trackRemoveFromCart );
		}

	}

	/**
	 * Verstuurt GA event op basis van data-attributes bij click
	 *
	 * @param {Event} event
	 */
	function _trackClick( event ) {
		var dataset = event.target.dataset;

		var type = dataset.gaType || '';
		var category = dataset.gaCategory || '';
		var action = dataset.gaAction || '';
		var label = dataset.gaLabel || '';
		ga( 'send', type, category, action, label);
		
	}

	/**
	 * Stuurt GA event bij het versturen van een Caldera Form
	 *
	 * @param {*} obj
	 */
	function trackFormSubmission( obj ) {
		ga( 'send', 'event', obj.form_id, 'Verzenden' );
	}

	/**
	 * Verstuurt GA-event voor verwijderen uit cart
	 *
	 * @param {Event} event
	 */
	function _trackRemoveFromCart( event ) {
		event.preventDefault();
		var variation_id = this.dataset.variation_id;
		ga( 'ec:addProduct', siw_analytics_cart[variation_id]);
		ga( 'ec:setAction', 'remove' );
		ga( 'send', 'event', 'Ecommerce', 'remove', 'remove from cart' );	
	}

})();

siwGoogleAnalytics.init();
