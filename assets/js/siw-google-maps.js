/**
 * @file      Functies t.b.v. Google Maps
 * @author    Maarten Bruna 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

siwGoogleMapsInitAll();

/**
 * Initialiseert alle kaarten
 */
function siwGoogleMapsInitAll() {
	//Zoek alle kaarten
	var maps = document.querySelectorAll( '.siw-google-map' );

	//Intialiseer elke kaart
	maps.forEach( function ( el, i ) {
		siwGoogleMapsInitSingle( el );
	})
}

/**
 * Initialiseert een kaart
 *
 * @param {Element} el
 */
function siwGoogleMapsInitSingle( el ) {
	var options = JSON.parse( el.dataset.options );

	//Zet locatie van 
	if ( typeof options.center === 'string' ) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': options.center }, function( results, status ) {
			if ( status === 'OK') {
				options.center = results[0].geometry.location;
				siwGoogleMapsCreate( el, options )
			}
			else {
				console.log( 'Center van kaart kan niet gevonden worden');
			}
		});
	} else if ( typeof options.center == 'object') {
		siwGoogleMapsCreate( el, options );
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
function siwGoogleMapsCreate( el, options ) {
	var map;
	map = new google.maps.Map( el, options );

	//Zoek alle markers
	var markers = el.dataset.markers;
	if ( typeof markers === 'string' ) {
		siwGoogleMapsAddMarkers( map, markers );
	}
}

/**
 * Voegt alle markers toe aan kaart
 *
 * @param {Map} map
 * @param {Array} markers
 */
function siwGoogleMapsAddMarkers( map, markers ) {
	JSON.parse( markers ).forEach( function( data ) {
		if ( typeof data.position === 'string' ) {
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': data.position }, function( results, status ) {
				if ( status === 'OK') {
					data.position = results[0].geometry.location;
					siwGoogleMapsAddMarker( map, data )
				} else {
					console.log( 'Locatie van marker kan niet gevonden worden');
				}
			} );
		} else if ( typeof data.position === 'object' ) {
			siwGoogleMapsAddMarker( map, data );
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
function siwGoogleMapsAddMarker( map, data ) {
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
