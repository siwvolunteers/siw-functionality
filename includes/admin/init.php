<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Admin
 * - Login
 * - User
 * - Interface
 * - Agenda
 * - Vacatures
 */
require_once( __DIR__ . '/login.php' );
require_once( __DIR__ . '/user.php' );
require_once( __DIR__ . '/interface.php' );
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/vacatures.php' );
