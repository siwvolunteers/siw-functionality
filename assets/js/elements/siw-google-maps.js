/** global: google */

/**
 * @file      Functies t.b.v. Google Maps
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */

var siwGoogleMaps = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle kaarten
	 */
	function init() {
		//Zoek alle kaarten
		var maps = document.querySelectorAll( '.siw-google-maps' );
		console.log(maps);

		//Intialiseer elke kaart
		for ( var i=0, len = maps.length; i < len; i++ ) {
			var map = maps[i];
			_initSingle( map );
		}
	}

	/**
	 * Initialiseert een kaart
	 *
	 * @param {Element} el
	 */
	function _initSingle( el ) {
		var options = JSON.parse( el.dataset.options );

		//Zet locatie van kaart
		if ( typeof options.center === 'string' ) {
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': options.center }, function( results, status ) {
				if ( status === 'OK') {
					options.center = results[0].geometry.location;
					_create( el, options )
				}
				else {
					console.log( 'Center van kaart kan niet gevonden worden');
				}
			});
		} else if ( typeof options.center == 'object') {
			_create( el, options );
		} else {
			console.log( 'Center van kaart kan niet bepaald worden' );
		}
	}

	/**
	 * Creëert kaart
	 *
	 * @param {Element} el
	 * @param {JSON} options
	 */
	function _create( el, options ) {
		var map;
		map = new google.maps.Map( el, options );

		//Zoek alle markers
		var markers = el.dataset.markers;
		if ( typeof markers === 'string' ) {
			_addMarkers( map, markers );
		}
	}

	/**
	 * Voegt alle markers toe aan kaart
	 *
	 * @param {Map} map
	 * @param {Array} markers
	 */
	function _addMarkers( map, markers ) {
		JSON.parse( markers ).forEach( function( data ) {
			if ( typeof data.position === 'string' ) {
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': data.position }, function( results, status ) {
					if ( status === 'OK') {
						data.position = results[0].geometry.location;
						_addMarker( map, data )
					} else {
						console.log( 'Locatie van marker kan niet gevonden worden');
					}
				} );
			} else if ( typeof data.position === 'object' ) {
				_addMarker( map, data );
			} else {
				console.log( 'Marker heeft geen locatie');
			}
		});
	}

	/**
	 * Voegt marker toe aan kaart
	 *
	 * @param {Map} map
	 * @param {JSON} data
	 */
	function _addMarker( map, data ) {

		var marker = new google.maps.Marker( {
			position: data.position,
			map: map,
			title: data.title
		});

		//Voeg content voor marker-popup toe
		var contentString = '<h5>' + data.title + '</h5><p>' + data.description + '</p>';
		var infowindow = new google.maps.InfoWindow( {
			content: contentString
		} );
		
		marker.addListener( 'click', function() {
			infowindow.open( map, marker );
		} );
	}

})();

siwGoogleMaps.init();