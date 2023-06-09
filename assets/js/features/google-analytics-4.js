/** global: siw_google_analytics_4 */

/**
 * @file      Functies t.b.v. Google Analytics 4
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */

var siwGoogleAnalytics4 = (function () {

	/* Public methodes */
	return {
		init: init,
	}

	/** Voegt listeners toe */
	function init() {

		window.dataLayer = window.dataLayer || [];
		function gtag(){
			dataLayer.push(arguments);
		}

		gtag('js', new Date());
		gtag('config', siw_google_analytics_4.measurement_id, siw_google_analytics_4.config_settings );
	}
})();

siwGoogleAnalytics4.init();
