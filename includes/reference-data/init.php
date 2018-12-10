<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Referentiegegevens
 */
require_once( __DIR__ . '/configuration/configuration.php' );
require_once( __DIR__ . '/continents/continents.php' );
require_once( __DIR__ . '/countries/countries.php' );
require_once( __DIR__ . '/currencies/currencies.php' );
require_once( __DIR__ . '/languages/languages.php' );
require_once( __DIR__ . '/social-networks/social-networks.php' );
require_once( __DIR__ . '/volunteer/volunteer.php' );
require_once( __DIR__ . '/work-types/work-types.php' );


/*
 * Referentiegegevens (oud)
 * - Constantes
 * - Valuta
 * - Landen
 * - Soort werk
 * - Talen
 * - Nationaliteiten
 */
require_once( __DIR__ . '/countries.php' );
require_once( __DIR__ . '/work.php' );
require_once( __DIR__ . '/languages.php' );
require_once( __DIR__ . '/volunteer.php' );
