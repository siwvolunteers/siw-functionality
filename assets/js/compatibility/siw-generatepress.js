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
			$( '.main-navigation .wc-menu-item' ).css( 'visibility', 'visible' );
			$( '.mobile-bar-items.wc-mobile-cart-items' ).css( 'visibility', 'visible' );

		}else {
			$( '.main-navigation .wc-menu-item' ).css( 'visibility', 'hidden' );
			$( '.mobile-bar-items.wc-mobile-cart-items' ).css( 'visibility', 'hidden' );
		}
	}

	$( document ).ready(function() {
		updateMenuCartVisibility();
	});
	$( document ).on( 'wc_fragments_refreshed', function(){
		updateMenuCartVisibility();
	});

})( jQuery );