/** global: Cookies, siw_cookie_notice */

/**
 * @file      Functies t.b.v. de cookie notice
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
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
		document.querySelector( '#' + siw_cookie_notice.notice_id + ' button[name="submit"]' ).addEventListener( 'click', _handleClick );
	}

	/**
	 * Toont de cookie notice als deze nog niet geaccepteerd is
	 */
	function _show () {
		if ( 'undefined' === typeof Cookies.get( siw_cookie_notice.cookie.name ) ) {
			document.querySelector( '#' + siw_cookie_notice.notice_id ).removeAttribute( 'hidden' );
		}
	};

	/**
	 * Verberg cookie notice en zet cookie
	 */
	function _handleClick ( element ) {
		element.preventDefault();
		formData = new FormData(document.querySelector( '#' + siw_cookie_notice.notice_id + ' form' ) );

		const data = {};
		formData.forEach((value, key) => (data[key] = value));
		Cookies.set( siw_cookie_notice.cookie.name, JSON.stringify(data), { expires: Number( siw_cookie_notice.cookie.expires ), secure: true } );
		document.querySelector( '#' + siw_cookie_notice.notice_id ).setAttribute( 'hidden', 'hidden' );


		if ( 'function' == typeof siwFacebookPixel.maybeGrantConsent) {
			siwFacebookPixel.maybeGrantConsent();
		}
	};

})();

siwCookieNotice.init();
