const siwIframeManager = (function () {

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
		const im = iframemanager();
		im.run(siw_iframe_manager.config);
	}

})();

siwIframeManager.init();
