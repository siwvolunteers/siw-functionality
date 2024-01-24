var siwIcons = (function () {

	return {
		init: init
	};

	function init () {
		var svgs = document.querySelectorAll( '[data-svg-url]' );
		for ( var i=0, len = svgs.length; i < len; i++ ) {
			var el = svgs[i];
			_loadSvg( el );
		}
	}

	/**
	 * @param {Element} target
	 */
	function _loadSvg( target ) {
		var url = target.dataset.svgUrl;
		var ajax = new XMLHttpRequest();
		ajax.open("GET", url, true);
		ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		ajax.responseType = 'text';
		ajax.send();

		ajax.onload = function(e) {
			target.innerHTML = ajax.response;
			if ( typeof target.dataset.viewbox !== 'undefined' ) {
				let svg = target.querySelector('svg');
				svg.setAttribute('viewBox', target.dataset.viewbox );
			}
		}
	}

})();

siwIcons.init();
