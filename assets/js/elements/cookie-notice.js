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

		// Voeg event listeners voor knoppen toe
		document.querySelector( '#' + siw_cookie_notice.notice_id + ' button[name="accept_selection"]' ).addEventListener( 'click', _handleAcceptSelection );
		document.querySelector( '#' + siw_cookie_notice.notice_id + ' button[name="accept_all"]' ).addEventListener( 'click', _handleAcceptAll );
	}

	/**
	 * Toont de cookie notice als deze nog niet geaccepteerd is
	 */
	function _show () {
		if ( 'undefined' === typeof Cookies.get( siw_cookie_notice.cookie.name ) ) {
			document.querySelector( '#' + siw_cookie_notice.notice_id ).removeAttribute( 'hidden' );
		}

		// FIXME: tijdelijk workaround voor Facebook Pixel
		document.body.dispatchEvent( new Event( siw_cookie_notice.event_name ) );
	};

	/** Handelt klik op 'Accepteer selectie' af */
	 function _handleAcceptSelection( element ) {
		_handleAccept(element, false);
	}

	/** Handelt klik op 'Accepteer alles' af */
	function _handleAcceptAll( element ) {
		_handleAccept(element, true);
	}

	/**
	 * Zet cookie, verberg notice en update Facebook pixel consent
	 *
	 * @param {Element} element
	 * @param {boolean} accept_all
	 */
	function _handleAccept( element, accept_all ) {
		element.preventDefault();
		formData = new FormData(document.querySelector( '#' + siw_cookie_notice.notice_id + ' form' ) );

		const data = {};

		if ( accept_all ) {
			data['analytical'] = '1';
			data['marketing'] = '1';
		} else {
			formData.forEach((value, key) => (data[key] = value));
		}
		Cookies.set( siw_cookie_notice.cookie.name, JSON.stringify(data), { expires: Number( siw_cookie_notice.cookie.expires ), secure: true, sameSite: 'strict' } );
		document.querySelector( '#' + siw_cookie_notice.notice_id ).setAttribute( 'hidden', 'hidden' );

		document.body.dispatchEvent( new Event( siw_cookie_notice.event_name ) );
	}

})();

siwCookieNotice.init();
