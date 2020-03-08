/** global: frappe */

/**
 * @file      Functies t.b.v. grafieken
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwCharts = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle grafieken
	 */
	function init () {
		//Zoek alle grafieken
		var charts = document.querySelectorAll( '.siw-chart' );

		//Initialiseer elke grafiek
		for ( var i=0, len = charts.length; i < len; i++ ) {
			var chart = charts[i];
			_initSingle( chart );
		}
	}

	/**
	 * Initialiseert 1 grafiek
	 *
	 * @param {Element} el
	 */
	function _initSingle( el ) {
		var options = JSON.parse( el.dataset.options );
		new frappe.Chart( el, options ); 
	}

})();

siwCharts.init();
