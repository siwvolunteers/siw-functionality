<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Widgets
 * - Agenda
 * - Contactgegevens
 * - Quotes van deelnemers
 * - Nieuwsbrief
 */
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/contact.php' );
require_once( __DIR__ . '/map.php' );
require_once( __DIR__ . '/newsletter.php' );
require_once( __DIR__ . '/quote.php' );
