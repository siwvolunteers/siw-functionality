<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-siw-cpt.php';

/*
 * Custom post types
 * - Agenda
 * - EVS-projecten
 * - Vacatures
 */
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/vacatures.php' );

require_once __DIR__ . '/tm-countries/tm-countries.php';
