/** global: siwGoogleAnalytics */

/**
 * @file      Analytics event voor Caldera Forms
 * @copyright 2015-2021 SIW Internationale Vrijwilligersprojecten
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
		jQuery( document ).on( 'cf.submission', _trackFormSubmission );
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
