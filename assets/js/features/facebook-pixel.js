var siwFacebookPixel = (function () {

	return {
		init: init,
	};

	function init() {

		if ( window.fbq ) {
			return;
		}
		n = window.fbq = function() {
			n.callMethod ? n.callMethod.apply(n,arguments) : n.queue.push(arguments)
		};
		if ( !window._fbq ) {
			window._fbq=n;
		}
		n.push=n;
		n.loaded=!0;
		n.version='2.0';
		n.queue=[];

		fbq( 'set', 'autoConfig', 'false', siw_facebook_pixel.pixel_id )
		fbq( 'init', siw_facebook_pixel.pixel_id) ;
		fbq( 'track', 'PageView' );
	}
})();

siwFacebookPixel.init();
