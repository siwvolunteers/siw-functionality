/** global: mapplic */

/**
 * @file      Functies t.b.v. Mapplic-kaarten
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

jQuery( '.siw-interactive-map' ).each( function( index, element ) {
	var map = jQuery( element );
	var options = map.data( 'options' );
	map.mapplic( options );
});