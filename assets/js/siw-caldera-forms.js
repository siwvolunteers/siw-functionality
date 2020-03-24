/** global: siwPostcodeApi, siwGoogleAnalytics */

/**
 * @file      Postcode lookup voor Caldera Forms
 * @copyright 2015-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

var siwCalderaForms = (function () {
	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Init
	 */
	function init () {
		jQuery( document ).on( 'cf.form.init', _addPostcodeHandler );
		jQuery( document ).on( 'cf.submission', _trackFormSubmission );
	}

	/**
	 * Voegt postcode lookup toe
	 * 
	 * @param {Event} event 
	 * @param {Object} data 
	 */
	function _addPostcodeHandler( event, data ) {
		var form = document.getElementById( data.idAttr );
		if ( form.dataset.siwPostcodeLookup ) {
			var suffix = data.idAttr.replace( data.formId, '');
			var postcode_id = 'postcode' + suffix;
			var housenumber_id = 'huisnummer' + suffix;
			var street_id = 'straat' + suffix;
			var city_id = 'woonplaats' + suffix;
			if ( 'function' == typeof siwPostcodeApi.addHandler ) {
				siwPostcodeApi.addHandler( postcode_id, housenumber_id, street_id, city_id );
			}
		}
	}

	/**
	 * Stuurt GA-event voor verzenden formulier
	 * 
	 * @param {Event} event 
	 * @param {Object} data 
	 */
	function _trackFormSubmission( event, obj ) {
		if ( 'function' == typeof siwGoogleAnalytics.trackFormSubmission ) {
			siwGoogleAnalytics.trackFormSubmission( obj.data );
		}
	}

})();

siwCalderaForms.init();
