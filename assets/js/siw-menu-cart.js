/** global: Cookies */

/**
 * @file      Functies t.b.v cart menu
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

(function( $ ) {
	/**
	 * Werkt zichtbaarheid van cart menu bij
	 */
	function updateMenuCartVisibility() {
		if ( Cookies.get( 'woocommerce_items_in_cart' ) > 0 ) {
			$( 'li.menu-cart' ).show();
		}else {
			$( 'li.menu-cart' ).hide();
		}
	}

	$( document ).ready(function() {
		updateMenuCartVisibility();
	});
	$( document ).on( 'wc_fragments_refreshed', function(){
		updateMenuCartVisibility();
	});

})( jQuery );