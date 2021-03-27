/** global: siw_api_postcode, URL */

/**
 * @file      Function t.b.v. postcode lookup
 * @copyright 2015-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */


var siwPostcodeApi = (function () {
	/* Public methodes */
	return {
		lookup: lookup,
		addHandler: addHandler
	};

	/**
	 * Postcode opzoeken bij externe bron
	 * 
	 * @param {string} postcode 
	 * @param {string} housenumber 
	 * @param {Function} callback 
	 */
	function lookup( postcode, housenumber, callback ) {

		//Check input
		if (
			! RegExp( siw_api_postcode_lookup.regex.postcode ).test( postcode )
			|| ! RegExp( siw_api_postcode_lookup.regex.housenumber ).test( housenumber )
			|| typeof callback !== 'function'
			) {
			return;
		}

		//URL opbouwen
		var url = new URL( siw_api_postcode_lookup.url );
		url.searchParams.set( 'postcode', postcode );
		url.searchParams.set( 'housenumber', housenumber );

		//Ajax-request sturen
		var ajax = new XMLHttpRequest();
		ajax.open( 'GET', url, true );
		ajax.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
		ajax.setRequestHeader( 'X-WP-Nonce', siw_api_postcode_lookup.nonce );
		ajax.responseType = 'json';
		ajax.send();

		ajax.onload = function() {
			callback( ajax.status, ajax.response.data );
		}
		ajax.onerror = function() {
			callback( ajax.status, null );
		}
	}

	/**
	 * Voegt handler vooer postcode-velden toe
	 * 
	 * @param {string} postcode_id 
	 * @param {string} housenumber_id 
	 * @param {string} street_id 
	 * @param {string} city_id 
	 */
	function addHandler( postcode_id, housenumber_id, street_id, city_id ) {

		//Toevoeg als document geladen is
		if ( document.readyState !== "loading" ) {
			_addHandler();
		} else {
			document.addEventListener( 'DOMContentLoaded', _addHandler );
		}
		
		//Voegt handler toe
		function _addHandler() {
			var postcode_el = document.getElementById( postcode_id );
			var housenumber_el = document.getElementById( housenumber_id );
			var street_el = document.getElementById( street_id );
			var city_el = document.getElementById( city_id );

			//Readonly maken
			street_el.setAttribute( 'readonly', true );
			city_el.setAttribute( 'readonly', true );
			
			// Callback voor postcode api
			var api_callback = function ( status, data ) {
				if ( 200 === status ) {
					var street = data.street;
					var city = data.city;
					street_el.setAttribute( 'readonly', true );
					city_el.setAttribute( 'readonly', true );
				}
				else {
					var street = '';
					var city = '';
					street_el.removeAttribute( 'readonly' );
					city_el.removeAttribute( 'readonly' );
				}

				// Zet waarde van straat en plaats
				street_el.value = street;
				city_el.value = city;
			}

			//Functie voor listener
			var _handleCallback = function() {
				siwPostcodeApi.lookup( postcode_el.value, housenumber_el.value, api_callback );
			}

			//Voeg listener toe
			postcode_el.addEventListener( 'change', _handleCallback );
			housenumber_el.addEventListener( 'change', _handleCallback );
		}
	}

})();
