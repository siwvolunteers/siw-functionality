<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * AJAX-functionaliteit
 * - Aanmelding nieuwsbrief
 * - Opzoeken postcode
 */
require_once( __DIR__ . '/newsletter-subscription.php' );
require_once( __DIR__ . '/postcode-lookup.php' );



/**
 * Hulpfunctie om ajax-actie toe te voegen
 * @param [type] $action [description]
 */
function siw_register_ajax_action( $action ) {
	add_filter( 'siw_ajax_allowed_actions', function( $actions ) {
		$actions[] = $action;
		return $actions;
	});
}
