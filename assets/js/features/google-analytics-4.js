/** global: siw_google_analytics_4 */

/**
 * @file      Functies t.b.v. Google Analytics 4
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */

var siwGoogleAnalytics4 = (function () {

	/* Public methodes */
	return {
		init: init,
		trackFormSubmission: trackFormSubmission
	}

	function gtag(){
		dataLayer.push(arguments);
	}

	/** Init */
	function init() {

		window.dataLayer = window.dataLayer || [];
		gtag('js', new Date());
		gtag('config', siw_google_analytics_4.measurement_id, siw_google_analytics_4.config );

		if ( 'undefined' !== typeof( siw_google_analytics_4.ecommerce_event ) ) {
			gtag('event', siw_google_analytics_4.ecommerce_event.name, siw_google_analytics_4.ecommerce_event.parameters )
		}

		// Voeg eventlisteners voor events toe
		let tracking_links = document.querySelectorAll( '[data-ga4-event]' );
		for ( let i=0, len = tracking_links.length; i < len; i++ ) {
			let tracking_link = tracking_links[i];
			tracking_link.addEventListener( 'click', _trackClick );
		}
	}

	/**
	 * Verstuurt GA event op basis van data-attributes bij click
	 *
	 * @param {Event} event
	 */
	function _trackClick( event ) {
		let dataset = event.currentTarget.dataset;
		if ( 'undefined' !== typeof( dataset.ga4Event ) ) {
			let ga4_event = JSON.parse(dataset.ga4Event);
			gtag('event', ga4_event.name, ga4_event.parameters);
		}
	}

	/**
	 * Stuurt GA event bij het versturen van een Formulier
	 *
	 * @param {string} form_id
	 */
	function trackFormSubmission( form_id ) {
		gtag( 'event', 'form_submit', {
			form_id: form_id
		});
	}

})();

siwGoogleAnalytics4.init();
