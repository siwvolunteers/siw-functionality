var siwCharts = (function () {

	return {
		init: init
	};

	function init () {
		var charts = document.querySelectorAll( '.siw-chart' );


		for ( var i=0, len = charts.length; i < len; i++ ) {
			var chart = charts[i];
			_initSingle( chart );
		}
	}

	/**
	 * @param {Element} el
	 */
	function _initSingle( el ) {
		var options = JSON.parse( el.dataset.options );
		new frappe.Chart( el, options );
	}

})();

siwCharts.init();
