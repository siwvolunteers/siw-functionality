<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Tellen projecten */
require_once( __DIR__ . '/count-workcamps.php' );
/* Bijwerken tarieven */
require_once( __DIR__ . '/update-workcamp-tariffs.php' );
/* Verbergen projecten */
require_once( __DIR__ . '/hide-workcamps.php' );
/* Verwijderen aanmeldingen */
require_once( __DIR__ . '/delete-applications.php' );
/* Verwijderen projecten */
require_once( __DIR__ . '/delete-workcamps.php' );
/* Repareren projecten */
require_once( __DIR__ . '/repair-workcamps.php' );



/**
 * Hulpfunctie om background proces te starten
 *
 * @param string $name
 * @param array $data
 * @return void
 */
function siw_start_background_process( $name, $data ) {

	$batch_size = 500;
	//$batch_size = ini_get( 'max_input_vars' ) - 100 
	//$batch_size = max( $batch_size, 100 );

	$batches = array_chunk( $data, $batch_size );

	$process = $GLOBALS[ $name ];
	foreach ( $batches as $batch ) {
		foreach ( $batch as $item ) {
			$process->push_to_queue( $item );
		}
		$process->save();
	}
	$process->dispatch();

}
