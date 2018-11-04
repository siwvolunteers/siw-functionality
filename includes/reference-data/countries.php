<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft array met Nederlandse provincies terug
 * @return array
 */
function siw_get_dutch_provinces() {
	$dutch_provinces = array(
		'nb' => __( 'Brabant', 'siw' ),
		'dr' => __( 'Drenthe', 'siw' ),
		'fl' => __( 'Flevoland', 'siw' ),
		'fr' => __( 'Friesland', 'siw' ),
		'ge' => __( 'Gelderland', 'siw' ),
		'gr' => __( 'Groningen', 'siw' ),
		'li' => __( 'Limburg', 'siw' ),
		'nh' => __( 'Noord-Holland', 'siw' ),
		'ov' => __( 'Overijssel', 'siw' ),
		'ut' => __( 'Utrecht', 'siw' ),
		'ze' => __( 'Zeeland', 'siw' ),
		'zh' => __( 'Zuid-Holland', 'siw' ),
	);
	return $dutch_provinces;
}