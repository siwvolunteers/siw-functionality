/**
 * @file      Functies t.b.v. accordions
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwAccordion = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle accordions
	 */
	function init () {
		//Zoek alle accordions
		var accordions = document.querySelectorAll( '.siw-accordion' );

		//Initialiseer elke accordion
		for ( var i=0, len = accordions.length; i < len; i++ ) {
			var accordion = new window.Accordion( accordions[i] );
			accordion.on( 'hide', _updateMaxHeight );
			accordion.on( 'show', _updateMaxHeight );
			accordion.mount();
		}
	}

	/**
	 * Werkt max-height van panel bij (t.b.v. animatie)
	 *
	 * @param {Element} header
	 * @param {Element} panel
	 */
	function _updateMaxHeight( header, panel ) {
		panel.style.maxHeight = panel.scrollHeight + "px";
	}

})();

siwAccordion.init();
