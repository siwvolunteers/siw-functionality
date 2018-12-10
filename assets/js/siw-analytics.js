/**
 * @file Functies t.b.v. Google Analytics
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */


/**
 * Wrapper om ga i.v.m. ingelogde gebruikers
 *
 * @param  {string} type
 * @param  {string} category
 * @param  {string} action
 * @param  {string} label
 */
function siwGa( type, category, action, label ) {
	if ( 'function' == typeof ga ) {
		ga( 'send', type, category, action, label );
	}
}

/**
 * Stuurt GA event bij het versturen van een Caldera Form
 *
 * @param {*} obj
 */
function siwSendGaFormSubmissionEvent( obj ) {
	siwGa( 'event', obj.form_id, 'Verzenden' );
}



(function( $ ) {
	/* GA-events:
	 * - Klikken topbar
	 * - Social shares
	 * - Downloaden document
	 * - Klikken op externe link
	 */
	$( document ).on( 'click', '#topbar_link', function() {
		siwGa( 'event', 'Topbar', 'Klikken', this.href );
	});

	$( document ).on( 'click', '.siw-social .facebook', function() {
		siwGa( 'social', 'Facebook', 'Delen', window.location.href );
	});

	$( document ).on( 'click', '.siw-social .twitter', function() {
		siwGa( 'social', 'Twitter', 'Delen', window.location.href );
	});

	$( document ).on( 'click', '.siw-social .linkedin', function() {
		siwGa( 'social', 'LinkedIn', 'Delen', window.location.href );
	});

	$( document ).on( 'click', '.siw-download', function() {
		siwGa( 'event', 'Document', 'Downloaden', this.href );
	});

	$( document ).on( 'click', '.siw-external-link', function() {
		siwGa( 'event', 'Externe link', 'Klikken', this.href );
	});

})( jQuery );
