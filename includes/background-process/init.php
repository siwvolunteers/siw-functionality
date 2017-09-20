<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Bijwerken tarieven */
require_once( __DIR__ . '/update-workcamp-tariffs.php' );
/* Verbergen projecten */
require_once( __DIR__ . '/hide-workcamps.php' );
