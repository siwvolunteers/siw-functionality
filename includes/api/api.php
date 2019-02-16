<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


require_once( __DIR__ . '/abstract-siw-api.php' );
require_once( __DIR__ . '/class-siw-api-newsletter-subscribe.php');
require_once( __DIR__ . '/class-siw-api-postcode-lookup.php');
add_action('plugins_loaded', [ 'SIW_API_Newsletter_Subscribe', 'init']);
add_action('plugins_loaded', [ 'SIW_API_Postcode_Lookup', 'init']);