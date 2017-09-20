<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Custom post types
 * - Agenda
 * - EVS-projecten
 * - Vacatures
 */
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/evs-projects.php' );
require_once( __DIR__ . '/vacatures.php' );
