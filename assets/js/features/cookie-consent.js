const siwCookieConsent = (function () {

	/* Public methodes */
	return {
		init: init
	};

	function init () {
		if ( document.readyState !== "loading" ) {
			_load();
		} else {
			document.addEventListener( 'DOMContentLoaded', _load );
		}
	}

	function _load () {
		const cc = initCookieConsent();
		console.log(document.readyState);
		cc.run(
			siw_cookie_consent.config
		);
	}

})();

siwCookieConsent.init();
