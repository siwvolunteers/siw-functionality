<?php declare(strict_types=1);

/**
 * Functies m.b.t. kortingsacties
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

 /** Geeft aan of kortingsactie voor Groepsprojecten actief is */
function siw_is_workcamp_sale_active() : bool {
	
	$workcamp_sale_active = false;

	if ( siw_get_option( 'workcamp_sale.active' ) &&
		date( 'Y-m-d' ) >= siw_get_option( 'workcamp_sale.start_date' ) &&
		date( 'Y-m-d' ) <= siw_get_option( 'workcamp_sale.end_date' )
		) {
			$workcamp_sale_active = true;
	}
	return $workcamp_sale_active;
}

/** Geeft aan of kortingsactie voor Projecten Op Maat actief is */
function siw_is_tailor_made_sale_active() : bool {
	$tailor_made_sale_active = false;

	if ( siw_get_option( 'tailor_made_sale.active' ) &&
		date( 'Y-m-d' ) >= siw_get_option( 'tailor_made_sale.start_date' ) &&
		date( 'Y-m-d' ) <= siw_get_option( 'tailor_made_sale.end_date' )
		) {
			$tailor_made_sale_active = true;
	}
	return $tailor_made_sale_active;
}
