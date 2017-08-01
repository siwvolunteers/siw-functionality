<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Klaarzetten acties voor verwerken plugin-update */
add_action( 'wppusher_plugin_was_updated', function() {
	wp_schedule_single_event( time(), 'siw_update_mailpoet_mail_template' );
});
