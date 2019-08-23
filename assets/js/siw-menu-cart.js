/** global: Cookies */

/**
 * @file      Functies t.b.v cart menu
 * @author    Maarten Bruna 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */


//Zichtbaarheid bijwerken na laden pagina
if ( document.readyState !== "loading" ) {
	siwMenuCartUpdateVisibility();
} else {
	document.addEventListener( 'DOMContentLoaded', siwMenuCartUpdateVisibility );
}

//Zichtbaarheid bijwerken na AJAX-refresh van cart
document.addEventListener( 'wc_fragments_refreshed', siwMenuCartUpdateVisibility );

/**
 * Werkt zichtbaarheid van menu cart bij
 */
function siwMenuCartUpdateVisibility() {
	if ( Cookies.get( 'woocommerce_items_in_cart' ) > 0 ) {
		document.querySelector( 'li.menu-cart').classList.remove( 'hidden' );
	} else {
		document.querySelector( 'li.menu-cart').classList.add( 'hidden' );
	}
}