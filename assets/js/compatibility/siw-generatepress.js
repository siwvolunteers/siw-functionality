/** global: Cookies */

/**
 * @file      Functies t.b.v GeneratePress
 * @copyright 21 SIW Internationale Vrijwilligersprojecten
 */

(function( $ ) {
	/**
	 * Werkt zichtbaarheid van cart menu bij
	 */
	function updateMenuCartVisibility() {
		if ( Cookies.get( 'woocommerce_items_in_cart' ) > 0 ) {
			$( '.main-navigation .wc-menu-item' ).show();
			$( '.mobile-bar-items.wc-mobile-cart-items' ).show();

		}else {
			$( '.main-navigation .wc-menu-item' ).hide();
			$( '.mobile-bar-items.wc-mobile-cart-items' ).hide();
		}
	}

	$( document ).ready(function() {
		updateMenuCartVisibility();
	});
	$( document ).on( 'wc_fragments_refreshed', function(){
		updateMenuCartVisibility();
	});

})( jQuery );