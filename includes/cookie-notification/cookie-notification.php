<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . '/class-siw-cookie-notification.php' );


/** Cookie notificatie toevoegen aan footer  */
$cookie_notification = new SIW_Cookie_Notification;
add_action( 'wp_footer', [ $cookie_notification, 'render'] );