/*
(c)2015-2017 SIW Internationale vrijwilligersprojecten
*/


function siwPostcodeLookup( postcodeSelector, housenumberSelector, streetSelector, citySelector ) {
	var postcode = jQuery( postcodeSelector ).val().replace( / /g, '' ).toUpperCase();
	var housenumber = jQuery( housenumberSelector ).val();
	var housenumber = housenumber.replace( /[^0-9]/g, '' );

	if ( ( '' != postcode ) && ( '' != housenumber ) ) {
		jQuery.ajax({
			url: siw.ajax_url,
			type: 'get',
			dataType: 'json',
			data: {
				action: 'postcode_lookup',
				postcode: postcode,
				housenumber: housenumber,
				security: siw.ajax_nonce
			},
			success: function( result ) {
				if ( true == result.success ) {
					jQuery( citySelector ).val( result.data.city ).prop( 'readonly', true );
					jQuery( streetSelector ).val( result.data.street ).prop( 'readonly', true );
				}else {
					jQuery( citySelector + ', ' + streetSelector ).val( '' ).prop( 'readonly', false );
				}
			}
		});
	}
return false;
}

(function( $ ) {

	//Validatieregel voor e-mail
	var validations = {
		email: [/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/, siw.invalid_email]
	};

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

	$( 'input.postcode, input.huisnummer' ).change(function() {
		siwPostcodeLookup( 'input.postcode', 'input.huisnummer', 'input.straat', 'input.plaats' );
		return false;
	});

	$( 'input[name="postcode"], input[name="huisnummer"]' ).change(function() {
		siwPostcodeLookup( 'input[name="postcode"]', 'input[name="huisnummer"]', 'input[name="straat"]', 'input[name="woonplaats"]' );
		return false;
	});

	//Scroll naar boven na ajax-filtering
	$( document ).on( 'yith-wcan-ajax-filtered', function() {
		$( document ).scrollTo( $( '.kad-shop-top' ), 800 );
	});



	$( '#siw_newsletter_subscription' ).submit(function( event ) {

		var name = $( '#newsletter_name' ).val();
		var email = $( '#newsletter_email' ).val();
		var list = $( '#newsletter_list_id' ).val();
		var nonce = $( '#newsletter_nonce' ).val();
		event.preventDefault();

		if ( ( '' != name ) && ( '' != email ) && ( '' != list ) ) {
			$( '#siw_newsletter_subscription' ).addClass( 'hidden' );
			$( '#newsletter_loading' ).removeClass( 'hidden' );
			$.ajax({
				url: siw.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'newsletter_subscription',
					name: name,
					email: email,
					list: list,
					security: nonce
				},
				success: function( result ) {
					$( '#newsletter_message' ).removeClass( 'hidden' );
					$( '#newsletter_loading' ).addClass( 'hidden' );
					$( '#newsletter_message' ).text( result.data.message );
					if ( true == result.success ) {
						siwGa( 'event', 'Nieuwsbrief', 'Aanmelden' );
					}
				}
			});

		}
	});

	$( document ).ready(function() {
		$( '#newsletter_email' ).change( function() {
			validation = new RegExp( validations.email[0] );
			if ( ! validation.test( this.value ) ) {
				this.setCustomValidity( validations.email[1] );
				return false;
			} else {
				this.setCustomValidity( '' );
			}
		});
	});
})( jQuery );
