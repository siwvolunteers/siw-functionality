/** global: Cookies */

/**
 * @file Functies t.b.v. de cookie notice
 * @author Maarten Bruna 
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

(function( $ ) {

	/**
	 * Verberg cookie notice als de gebruiker op accepteren klikt
	 */
	function hideCookieNotice() {
		Cookies.set( 'siw_cookie_consent', 'yes', { expires: 365, secure: true } );
		$( '#siw-cookie-notification' ).hide();
	}
	$( document ).on( 'click', '#siw-cookie-consent', hideCookieNotice);
	

	/**
	 * Toont de cookie notice als deze nog niet geaccepteerd is
	 */
	function showCookieNotice() {
		if ( 'yes' !== Cookies.get( 'siw_cookie_consent' ) ) {
			$( '#siw-cookie-notification' ).show();
		}
	}
	$( document ).ready( showCookieNotice );

})( jQuery );