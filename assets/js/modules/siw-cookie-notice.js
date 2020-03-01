/** global: Cookies */

/**
 * @file      Functies t.b.v. de cookie notice
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwCookieNotice = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert cookie notice
	 */
	function init () {
		//Cookie notice tonen na laden pagina
		if ( document.readyState !== "loading" ) {
			_show();
		} else {
			document.addEventListener( 'DOMContentLoaded', _show );
		}
		//Verbergen na klikken op knop
		document.querySelector( '#siw-cookie-consent' ).addEventListener( 'click', _hide );
	}

	/**
	 * Toont de cookie notice als deze nog niet geaccepteerd is
	 */
	function _show () {
		if ( 'yes' !== Cookies.get( 'siw_cookie_consent' ) ) {
			document.querySelector( '#siw-cookie-notification' ).classList.remove( 'hidden' );
		}
	};

	/**
	 * Verberg cookie notice en zet cookie
	 */
	function _hide () {
		Cookies.set( 'siw_cookie_consent', 'yes', { expires: 365, secure: true } );
		document.querySelector( '#siw-cookie-notification' ).classList.add( 'hidden' );
	};


})();

siwCookieNotice.init();
