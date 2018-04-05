<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'WC_LOG_HANDLER', 'WC_Log_Handler_DB' );

add_filter( 'woocommerce_status_log_items_per_page', function( $per_page ) {
    $per_page = 25;
    return $per_page;
});

add_filter( 'woocommerce_register_log_handlers', function( $handlers ) {
  
    $wc_log_handler_db = new WC_Log_Handler_DB;
    $wc_log_handler_email = new WC_Log_Handler_Email;

    $wc_log_handler_email->set_threshold( 'alert' );

    $handlers = array(
        $wc_log_handler_db,
        $wc_log_handler_email,
    );

    return $handlers;
}, 99 );
