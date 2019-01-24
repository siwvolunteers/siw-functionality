<?php
/**
 * Achtergrond processen
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Generieke klasse */
require_once( __DIR__ . '/class-siw-background-process.php' );
/* Background processen */
require_once( __DIR__ . '/class-siw-count-workcamps.php' );
require_once( __DIR__ . '/class-siw-update-workcamp-tariffs.php' );
require_once( __DIR__ . '/class-siw-hide-workcamps.php' );
require_once( __DIR__ . '/class-siw-delete-applications.php' );
require_once( __DIR__ . '/class-siw-delete-workcamps.php' );
require_once( __DIR__ . '/class-siw-update-free-places.php' );
require_once( __DIR__ . '/class-siw-delete-orphaned-variations.php' );
require_once( __DIR__ . '/class-siw-update-taxonomies.php' );


/**
 * Start achtergrondproces
 *
 * @param string $action
 *
 * @return void
 */
function siw_start_background_process( $action ) {
	$process_name = 'siw_' .  $action . '_process';

	if ( ! isset( $GLOBALS[ $process_name ] ) ) {
		return;
	}
	$process = $GLOBALS[ $process_name ];

	$process->start();
}


/**
 * Registreert een achtergrondproces
 *
 * @param string $class
 * @param string $action
 * @param string $node
 * @param array $parent_nodes
 * @param bool $add_cron_job
 * @return void
 */
function siw_register_background_process( $class, $action, $node, $parent_nodes = array(), $add_cron_job = true ) {

	/* Afbreken als $class niet bestaat of geen subklasse van SIW_Background_Process is */
	if ( ! class_exists( $class ) ) {
		return;
	}
	$process = new $class();

	if ( ! is_subclass_of( $process, 'SIW_Background_Process') ) {
		return;
	}
	$GLOBALS['siw_' . $action . '_process'] = new $class();

	/**
	 * Toevoegen aan admin bar
	 */
	if ( ! empty( $parent_nodes ) ) {
		foreach ( $parent_nodes as $admin_node => $properties ) { 
			siw_add_admin_bar_node( $admin_node, $properties );
		}
	}
	siw_add_admin_bar_action( $action, $node );

	/**
	 * Cron job toevoegen
	 */
	if ( true == $add_cron_job ) {
		SIW_Scheduler::add_job( "siw_{$action}" );
	}

	add_action( 'siw_'. $action, function() use( $action ) {
		siw_start_background_process( $action );
	});
}
