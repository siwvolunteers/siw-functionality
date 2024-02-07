const siwCookieConsent = (function () {

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
		CookieConsent.run( siw_cookie_consent );
	}

})();

siwCookieConsent.init();
