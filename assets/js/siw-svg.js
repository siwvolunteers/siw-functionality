/**
 * @file      Functies t.b.v. svgs
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

var siwSvg = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Init
	 */
	function init () {
		var svgs = document.querySelectorAll( '[data-svg-url]' );
		for ( var i=0, len = svgs.length; i < len; i++ ) {
			var el = svgs[i];
			_loadSvg( el );
		}
	}

	/**
	 * Laad SVG bestand inline
	 *
	 * @param {Element} target
	 */
	function _loadSvg( target ) {

		var url = target.dataset.svgUrl;
		
		//Haal svg via ajax op
		var ajax = new XMLHttpRequest();
		ajax.open("GET", url, true);
		ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		ajax.responseType = 'text';
		ajax.send();

		// Zet SVG inline
		ajax.onload = function(e) {
			target.innerHTML = ajax.response;
		}
	}

})();

siwSvg.init();
