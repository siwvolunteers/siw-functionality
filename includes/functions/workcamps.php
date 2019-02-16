<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




/**
 * Bepaal of kortingsactie actief is
 *
 * @return bool
 */
function siw_is_sale_active() {

	$sale_active = false;

	if ( siw_get_setting( 'workcamp_sale_active' ) &&
		date( 'Y-m-d' ) >= siw_get_setting( 'workcamp_sale_start_date' ) &&
		date( 'Y-m-d' ) <= siw_get_setting( 'workcamp_sale_end_date' )
		) {
			$sale_active = true;
	}

	return $sale_active;
}
