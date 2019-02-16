/**
 * @file Algemene functies
 * @author Maarten Bruna 
 * @copyright 2015-2018 SIW Internationale Vrijwilligersprojecten
 */


(function( $ ) {

	$( document ).ready(function() {

		//Cart laten verdwijnen als je ergens anders op het scherm klikt
		$( document ).on( 'click', function() {
			$( '.kad-head-cart-popup.in' ).collapse( 'hide' );
		});

		$( '.accordion-toggle' ).each(function() {
			$( this ).removeAttr( 'data-parent' );
		});

		//Winkelwagen verbergen indien er geen projecten in zitten
		if ( Cookies.get( 'woocommerce_items_in_cart' ) > 0 ) {
			$( 'li.menu-cart-icon-kt' ).show();
		}else {
			$( 'li.menu-cart-icon-kt' ).hide();
		}
	});


	//Scroll naar boven na ajax-filtering
	$( document ).on( 'yith-wcan-ajax-filtered', function() {
		$( document ).scrollTo( $( '.kad-shop-top' ), 800 );
	});

})( jQuery );
