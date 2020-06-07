/** global: siw_api_newsletter, ga */

/**
 * @file      Functies t.b.v. de nieuwsbrief signup
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      widget-functies naar eigen bestand afsplitsen
 */

var siwNewsletterSignup = (function () {
	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert nieuwsbriefaanmelding
	 */
	function init () {
		if ( document.readyState !== "loading" ) {
			_addListeners();
		} else {
			document.addEventListener( 'DOMContentLoaded', _addListeners );
		}
	}

	/**
	 * Voegt listeners toe
	 */
	function _addListeners() {
		var forms = document.querySelectorAll( 'form.newsletter_form');
		for ( var i=0, len = forms.length; i < len; i++ ) {
			var form = $forms[i];
			form.addEventListener('submit', _handleFormSubmit );
		}
	}

	/**
	 * Handelt verzenden van formulier af
	 * 
	 * @param {Event} event 
	 */
	function _handleFormSubmit( event ) {
		event.preventDefault();
		var form = event.target;
		var message = document.getElementById( form.dataset.messageId );
		var formdata = new FormData( form );

		//Ajax-request sturen
		var ajax = new XMLHttpRequest();
		ajax.open( 'POST', siw_api_newsletter.url, true );
		ajax.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
		ajax.setRequestHeader( 'X-WP-Nonce', siw_api_newsletter.nonce );
		ajax.responseType = 'json';
		ajax.send( formdata );

		//Formulier verbergen en loading animatie tonen
		form.classList.add( 'hidden' );
		message.classList.add( 'loading' );

		//Ajax-response afhandelen
		ajax.onload = function() {
			//Loading animatie verbergen en boodschap tonen
			message.classList.remove('loading');
			message.innerHTML = ajax.response.message;

			//GA-event bij succesvolle aanmelding
			if ( true === ajax.response.success ) {
				if ( 'function' == typeof ga ) {
					ga( 'send', 'event', 'Nieuwsbrief', 'Aanmelden' );
				}
			}
		};
	}
})();

siwNewsletterSignup.init();
