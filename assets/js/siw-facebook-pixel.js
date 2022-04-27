/** global: siw_facebook_pixel */
/**
 * @file      Functies t.b.v. Facebook pixel
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */

var siwFacebookPixel = (function () {

	/* Public methodes */
	return {
		init: init,
	};

	/** Init */
	function init() {

		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/nl_NL/fbevents.js');
		if ( ! _isConsentGiven() ) {
			fbq( 'consent', 'revoke' );
		}
		fbq( 'set', 'autoConfig', 'false', siw_facebook_pixel.pixel_id )
		fbq( 'init', siw_facebook_pixel.pixel_id) ;
		fbq( 'track', 'PageView' );

		// Event listener voor update van cookie choices
		document.body.addEventListener( siw_facebook_pixel.event_name, _maybeGrantConsent );
	}

	/** Eventueel consent zetten */
	function _maybeGrantConsent() {
		if ( _isConsentGiven() ) {
			fbq( 'consent', 'grant' );
		}
	}
	/** Bepaal op basis van cookie of toestemming gegeven is voor marketing cookies  */
	function _isConsentGiven() {
		cookieSettings = Cookies.get(siw_facebook_pixel.cookie_name );
		if ( 'string' !== typeof cookieSettings ) {
			return false;
		}
		return JSON.parse( cookieSettings ).marketing === '1';
	}

})();

siwFacebookPixel.init();
