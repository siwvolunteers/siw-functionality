<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . '/login.php' );
require_once( __DIR__ . '/interface.php' );
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/vacatures.php' );
require_once( __DIR__ . '/evs.php' );
require_once( __DIR__ . '/class-siw-transient-notices.php' );

require_once( __DIR__ . '/class-siw-properties-page.php' );
add_action( 'plugins_loaded', ['SIW_Properties_Page', 'init' ]);
