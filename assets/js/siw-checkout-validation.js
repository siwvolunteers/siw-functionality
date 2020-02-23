/** global: siw_checkout_postcode_selectors */

/**
 * @file      Checkout validatie
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwCheckoutValidation = (function () {
	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Init
	 */
	function init() {
		if ( document.readyState !== "loading" ) {
			_addValidation();
		} else {
			document.addEventListener( 'DOMContentLoaded', _addValidation );
		}
	}

	/**
	 * Voegt validatie toe
	 */
	function _addValidation() {
		//Plaats van melding aanpassen
		jQuery.validator.setDefaults({
			errorPlacement: function( error, element ) {
				error.appendTo( element.parents( 'p' ) );
			}
		});

		//Validatiemethodes toevoegen
		for ( var i=0, len = siw_checkout_validation.length; i < len; i++ ) {
			validation = siw_checkout_validation[i];
			_addMethod( siw_checkout_validation[i] );
		}
	}

	/**
	 * Voegt validatiemethode toe
	 * 
	 * @param {Object} method 
	 */
	function _addMethod( method ) {
		jQuery.validator.addMethod( method.class, function( value, element ) {
			return this.optional( element ) || RegExp( method.regex ).test( value );
		}, method.message );
	}
})();

siwCheckoutValidation.init();