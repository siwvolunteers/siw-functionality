/** global: Cookies */

/**
 * @file      Functies t.b.v. de cookie notice
 * @author    Maarten Bruna 
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

//Cookie notice tonen na laden pagina
if ( document.readyState !== "loading" ) {
	siwCookieNoticeShow();
} else {
	document.addEventListener( 'DOMContentLoaded', siwCookieNoticeShow );
}

//Verbergen na klikken op knop
document.querySelector( '#siw-cookie-consent' ).addEventListener( 'click', siwCookieNoticeHide );

/**
 * Verberg cookie notice als de gebruiker op accepteren klikt
 */
function siwCookieNoticeHide() {
	Cookies.set( 'siw_cookie_consent', 'yes', { expires: 365, secure: true } );
	document.querySelector( '#siw-cookie-notification' ).classList.add( 'hidden' );
}

/**
 * Toont de cookie notice als deze nog niet geaccepteerd is
 */
function siwCookieNoticeShow() {
	if ( 'yes' !== Cookies.get( 'siw_cookie_consent' ) ) {
		document.querySelector( '#siw-cookie-notification' ).classList.remove( 'hidden' );
	}
}