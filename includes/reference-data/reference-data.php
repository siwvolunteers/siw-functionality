<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Functies t.b.v. referentiegegevens
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

require_once( __DIR__ . '/continents/continents.php' );
require_once( __DIR__ . '/countries/countries.php' );
require_once( __DIR__ . '/currencies/currencies.php' );
require_once( __DIR__ . '/languages/languages.php' );
require_once( __DIR__ . '/social-networks/social-networks.php' );
//require_once( __DIR__ . '/user-roles/user-roles.php' );
require_once( __DIR__ . '/volunteer/volunteer.php' );
require_once( __DIR__ . '/work-types/work-types.php' );


/**
 * Geeft array met provincies van Nederland terug
 *
 * @return array
 */
function siw_get_dutch_provinces() {
	$dutch_provinces = [
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
	];
	return $dutch_provinces;
}

/**
 * Geeft array met bestuursfuncties terug
 * 
 * @return array
 */
function siw_get_board_titles() {
	$titles = [
		'chair'        => __( 'Voorzitter', 'siw' ),
		'secretary'    => __( 'Secretaris' , 'siw' ),
		'treasurer'    => __( 'Penningmeester' , 'siw' ),
		'board_member' => __( 'Algemeen bestuurslid' , 'siw' ),
	];
	return $titles;
}