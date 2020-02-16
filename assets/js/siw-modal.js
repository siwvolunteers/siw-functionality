/** global: MicroModal */

/**
 * @file      Functies t.b.v. modals
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

var siwModal = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Init
	 */
	function init () {
		if ( document.readyState !== "loading" ) {
			_init();
		} else {
			document.addEventListener( 'DOMContentLoaded', _init );
		}
	}

	/**
	 * Initialiseert alle modals en voegt event listeners toe
	 */
	function _init() {

		//Initialiseer modals
		MicroModal.init({
			disableScroll: siw_modal.disableScroll,
			disableFocus: siw_modal.disableFocus,
			awaitOpenAnimation: siw_modal.awaitOpenAnimation,
			awaitCloseAnimation: siw_modal.awaitCloseAnimation,
			debugMode: siw_modal.debugMode
		});

		//Zoek alle modal-knoppen
		var modals = document.querySelectorAll( 'a[data-micromodal-trigger]' );

		//Voeg event listener toe voor modal
		for ( var i=0, len = modals.length; i < len; i++ ) {
			var modal = modals[i];
			modal.addEventListener( 'click', __preventDefault );
		}
	}

	/**
	 * Voorkom dat link gevolgd wordt
	 *
	 * @param {Event} event
	 */
	function __preventDefault( event ) {
		event.preventDefault();
	}

})();

siwModal.init();