/**
 * @file      Functies t.b.v. Leaflet kaarten
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */

var siwLeafletMap = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle kaarten
	 */
	function init() {
		//Zoek alle kaarten
		var maps = document.querySelectorAll( '.siw-leaflet-map' );

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

		var mapOptions = JSON.parse( el.dataset.mapOptions );

		if ( typeof mapOptions.center.location === 'string' ) {
			_geocode( mapOptions.center.location, mapOptions.center.hash, function( coordinates ) {
				mapOptions.center = coordinates;
				_create( el, mapOptions )
			} );
		} else if ( typeof mapOptions.center.coordinates == 'object') {
			mapOptions.center = mapOptions.center.coordinates;
			_create( el, mapOptions );
		}
	}

	/**
	 * Creëert kaart
	 *
	 * @param {Element} el
	 * @param {JSON} mapOptions
	 */
	function _create( el, mapOptions ) {
		var map = L.map( el, mapOptions );

		var tileLayer = siwLeafletMapData.tileLayer;
		L.tileLayer( tileLayer.urlTemplate, tileLayer.options ).addTo( map );

		//Zoek alle markers
		var markers = el.dataset.markers;
		if ( typeof markers === 'string' ) {
			_addMarkers( map, markers );
		}

		// Kaart resizen als parent element zichtbaar wordt
		var observer = new MutationObserver( function() {
			if ( el.parentElement.style.display != 'none' ) {
				map.invalidateSize();
			}
		});
		observer.observe( el.parentElement, { attributeFilter: ['style'] } );
	}

	/**
	 * Voegt alle markers toe aan kaart
	 *
	 * @param {*} map
	 * @param {Array} markers
	 */
	function _addMarkers( map, markers ) {
		JSON.parse( markers ).forEach( function( data ) {
			if ( typeof data.location === 'string' ) {
				_geocode( data.location, data.hash, function( coordinates ) {
					data.coordinates = coordinates;
					_addMarker( map, data )
				} );
			} else if ( typeof data.coordinates === 'object' ) {
				_addMarker( map, data );
			}
		});
	}

	/**
	 * Voegt marker toe aan kaart
	 *
	 * @param {*} map
	 * @param {JSON} data
	 */
	function _addMarker( map, data ) {

		var marker = L.marker(
			data.coordinates,
			{
				title:data.title,
			}
		).addTo( map );

		let popupContent = '<h5>' + data.title + '</h5><p>' + data.description + '</p>';
		marker.bindPopup( popupContent )
	}

	/**
	 * Geocode adres
	 *
	 * @param {string} query
	 * @param {*} callback
	 */
	function _geocode( query, hash, callback ) {

		if ( typeof Storage !== 'undefined' ) {
			let coordinates = JSON.parse( localStorage.getItem( hash ) );
			if ( null !== coordinates ) {
				callback( coordinates );
				return;
			}
		}

		const url = new URL( siwLeafletMapData.geocodingUrl );

		url.searchParams.append( 'q', query );
		url.searchParams.append( 'format', 'json' );

		var ajax = new XMLHttpRequest();
		ajax.open( 'GET', url, true );
		ajax.responseType = 'json';
		ajax.send();

		ajax.onload = function() {
			let coordinates = [ Number( ajax.response[0].lat ), Number( ajax.response[0].lon ) ];

			if ( typeof Storage !== 'undefined' ) {
				localStorage.setItem( hash, JSON.stringify( coordinates ) );
			}
			callback( coordinates );
		}
	}
})();

siwLeafletMap.init();
