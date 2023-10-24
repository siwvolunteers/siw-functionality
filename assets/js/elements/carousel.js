var siwCarousel = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle carousels
	 */
	function init () {
		var carousels = document.querySelectorAll( '.siw-carousel' );
		for ( var i=0, len = carousels.length; i < len; i++ ) {
			new Splide( carousels[ i ] ).mount();
		}
	}
})();

siwCarousel.init();
