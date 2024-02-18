var siwGoogleTagManager = (function () {

	return {
		init: init,
	}

	function init() {
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push( 'consent', 'default', {
			'ad_storage': 'denied',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied',
			'analytics_storage': 'granted'
		});

		maybeUpdateConsent();

		window.dataLayer.push({
			'gtm.start':new Date().getTime(),
			event:'gtm.js'
		});

		window.addEventListener( 'cc:onFirstConsent', maybeUpdateConsent );
	}

	function maybeUpdateConsent() {
		if(CookieConsent.acceptedCategory( 'marketing' ) ) {
			window.dataLayer.push( 'consent', 'update', {
				'ad_storage': 'granted',
				'ad_user_data': 'granted',
				'ad_personalization': 'granted',
			});
		}
	}

})();

siwGoogleTagManager.init();
