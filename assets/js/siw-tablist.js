/**
 * @file      Functies t.b.v. tablists
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

var siwTablist = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle tablists
	 */
	function init () {
		//Zoek alle tablists
		var tablists = document.querySelectorAll( '[role="tablist"]' );

		//Initialiseer elke tablist
		for ( var i=0, len = tablists.length; i < len; i++ ) {
			var tablist = new window.Tablist( tablists[i] );
			tablist.mount();
		}
	}


})();

siwTablist.init();
