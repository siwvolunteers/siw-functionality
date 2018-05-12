<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Generieke klasse */
require_once( __DIR__ . '/siw-background-process.php' );
/* Background processen */
require_once( __DIR__ . '/count-workcamps.php' );
require_once( __DIR__ . '/update-workcamp-tariffs.php' );
require_once( __DIR__ . '/hide-workcamps.php' );
require_once( __DIR__ . '/delete-applications.php' );
require_once( __DIR__ . '/delete-workcamps.php' );
require_once( __DIR__ . '/delete-orphaned-variations.php' );


/**
 * Hulpfunctie om background proces te starten
 *
 * @param string $name
 * @param array $data
 * @param int $batch_size
 * @return void
 */
function siw_start_background_process( $name, $data, $log_context = '', $batch_size = 500 ) {

	$batches = array_chunk( $data, $batch_size );

	$process = $GLOBALS[ 'siw_' .  $name . '_process' ];
	foreach ( $batches as $batch ) {
		foreach ( $batch as $item ) {
			$process->push_to_queue( $item );
		}
		$process->save()->empty_queue();
	}
	$process->dispatch();
}
