/** global: mapplic */

/**
 * @file      Functies t.b.v. Mapplic-kaarten
 * @author    Maarten Bruna 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

jQuery( '.siw-interactive-map' ).each( function( index, element ) {
	var map = jQuery( element );
	var options = map.data( 'options' );
	map.mapplic( options );
});