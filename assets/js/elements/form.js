/** global: siwGoogleAnalytics, wp */

/**
 * @file      Functies t.b.v. formulieren
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @todo      Netter
 */


(function( $ ) {
	jQuery( '.siw-form' ).each( processForm );


	function processForm( index, element ) {

		let $this = $(element)
		let $form = $this.find( 'form' );

		$form.find( 'button[name=rwmb_submit]' ).on( 'click', handleSubmitClick );

		function handleSubmitClick( element ) {
			element.preventDefault();

			$( '#rwmb-validation-message' ).remove();
			if ( ! $.validator || ! $form.valid() ) {
				return;
			}
			addProcessing();
			performSubmit();
		}

		function addProcessing() {
			$this.addClass( 'processing');
		}

		function removeProcessing() {
			$this.removeClass( 'processing');
		}

		function performSubmit() {
			$this.find( '.message' ).remove();
			let data = new FormData( $form[ 0 ] );

			const scrollTo = $el => $( 'html, body' ).animate( { scrollTop: $el.offset().top - 50 }, 200 );

			wp.apiRequest( {
				method: 'POST',
				path: $form.data( 'apiPath' ),
				data: data,
				contentType: false,
				processData: false
			} ).done( function( response ) {

				success = true;

				if ( 'undefined' !== typeof( response.message ) ) {
					message = response.message;
				} else {
					message = 'Success';
				}

				if ( 'function' == typeof siwGoogleAnalytics.trackFormSubmission) {
					siwGoogleAnalytics.trackFormSubmission( $form.data( 'formId' ) );
				}
			}).fail( function( response ) {

				success = false;

				if ( 'undefined' !== typeof( response.responseJSON ) && 'undefined' !== typeof( response.responseJSON.message ) ) {
					message = response.responseJSON.message;
				} else {
					message = 'Error';
				}
			} ).always( function() {
				removeProcessing();
				addMessage( message, success)
				scrollTo( $this );
			});
		}

		function addMessage( message, success = true ) {
			message = `<div class="message ${ success ? 'success' : 'error' }">${ message }</div>`;

			if ( !success ) {
				$form.prepend( message );
			} else {
				$form.replaceWith( message );
			}
		}
	}
})
( jQuery );
