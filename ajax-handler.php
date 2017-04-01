<?php
/*
(c)2015-2017 SIW Internationale Vrijwilligersprojecten
*/

/* AJAX-handler */

define( 'DOING_AJAX', true);
if ( ! isset( $_REQUEST['action'] ) )
	die( '-1' );

require_once( '../../../wp-load.php' );

//zet headers
@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );
send_nosniff_header();
nocache_headers();


/* Actie uit request halen */
$action = esc_attr( trim( $_REQUEST['action']) );

/* Toegestane acties */
$allowed_actions = apply_filters( 'siw_ajax_allowed_actions', array() );

//uitvoeren toegestane actie
if ( in_array( $action, $allowed_actions ) ) {
	do_action( 'siw_ajax_'.$action );
} else {
	die( '-1' );
}
