var siwGoogleTagManager = (function () {

	return {
		init: init,
	}

	function init() {
		window['dataLayer']=window['dataLayer']||[];
		window['dataLayer'].push({
			'gtm.start':new Date().getTime(),
			event:'gtm.js'
		});
	}
})();

siwGoogleTagManager.init();
