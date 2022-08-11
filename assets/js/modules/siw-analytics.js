/** global: ga, siw_analytics */

/**
 * @file      Functies t.b.v. Google Analytics
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */

var siwGoogleAnalytics = (function () {

	/* Public methodes */
	return {
		init: init,
		trackFormSubmission: trackFormSubmission
	};

	/** Voegt listeners toe */
	function init() {

		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		ga('create', siw_analytics.property_id, siw_analytics.tracker_settings);

		for ( const [key, value] of Object.entries(siw_analytics.tracker_options ) ) {
			ga( 'set', key, value );
		}

		// Ecommerce tracking
		if ( 'undefined' !== typeof( siw_analytics.ecommerce_data ) ) {
			ga( 'require', 'ec' );

			// Impressions
			if ( 'undefined' !== typeof( siw_analytics.ecommerce_data.impressions) ) {
				for ( let i=0, len = siw_analytics.ecommerce_data.impressions.length; i < len; i++ ) {
					ga( 'ec:addImpression', siw_analytics.ecommerce_data.impressions[ i ]);
				}
			}

			// Producten
			if ( 'undefined' !== typeof( siw_analytics.ecommerce_data.products ) ) {
				for ( let i=0, len = siw_analytics.ecommerce_data.products.length; i < len; i++ ) {
					ga( 'ec:addProduct', siw_analytics.ecommerce_data.products[ i ] );
				}
			}

			if ( 'undefined' !== typeof( siw_analytics.ecommerce_data.action ) ) {
				if ( 'undefined' !== typeof siw_analytics.ecommerce_data.action_data ) {
					ga( 'ec:setAction', siw_analytics.ecommerce_data.action, siw_analytics.ecommerce_data.action_data );
				} else {
					ga( 'ec:setAction', siw_analytics.ecommerce_data.action );
				}
			}

		}

		ga('send', 'pageview');

		// Voeg eventlisteners voor links toe
		let tracking_links = document.querySelectorAll( '[data-ga-track="1"]' );
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

		let type = dataset.gaType || '';
		let category = dataset.gaCategory || '';
		let action = dataset.gaAction || '';
		let label = dataset.gaLabel || '';

		let ec_product = dataset.ecProduct;
		let ec_action = dataset.ecAction;
		let ec_action_data = dataset.ecActionData;

		if ( 'undefined' !== typeof( ec_product ) ) {
			ga( 'ec:addProduct', JSON.parse( ec_product ) );
		}
		if ( 'undefined' !== typeof( ec_action ) ) {
			if ( 'undefined' !== typeof( ec_action_data ) ) {
				ga( 'ec:setAction', ec_action, JSON.parse( ec_action_data ) );
			} else {
				ga( 'ec:setAction', ec_action );
			}

		}
		ga( 'send', type, category, action, label );
	}

	/**
	 * Stuurt GA event bij het versturen van een Formulier
	 *
	 * @param {string} form_id
	 */
	function trackFormSubmission( form_id ) {
		ga( 'send', 'event', 'form', 'send', form_id );
	}

})();

siwGoogleAnalytics.init();
